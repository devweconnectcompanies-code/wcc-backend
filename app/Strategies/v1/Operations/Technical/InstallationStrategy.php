<?php

namespace App\Strategies\v1\Operations\Technical;


use App\DTOs\v1\management\services\ServiceDTO;
use App\DTOs\v1\management\services\ServiceInternetDTO;
use App\Enums\v1\General\CommonStatus;
use App\Enums\v1\Supports\SupportStatus;
use App\Models\Clients\ClientModel;
use App\Models\Infrastructure\Network\NodeModel;
use App\Models\Management\Profiles\InternetModel;
use App\Models\Services\ServiceInternetModel;
use App\Models\Services\ServiceModel;
use App\Models\Supports\SupportModel;
use App\Services\v1\network\MikrotikInternetService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class InstallationStrategy extends BaseSupportStrategy
{
    /***
     * @param MikrotikInternetService $mikrotikInternetService
     */
    public function __construct(private MikrotikInternetService $mikrotikInternetService)
    {

    }

    /***
     * @param SupportModel $model
     * @param array $params
     * @return SupportModel
     */
    public function handle(SupportModel $model, array $params): SupportModel
    {
        $status = SupportStatus::tryFrom((int)$params['status']);
        if (!$status || !$status->isFinalized()) return $model;
        if ($model->service_id) return $model;

        $this->validateClientStatus($params['client']);
        try {
            DB::transaction(function () use ($model, $params) {
                $service = $this->checkPreviousService($model->client_id, $params);
                $this->updateSupport($model, $service->id, $params);
            });

        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'process' => "No se pudo completar la instalación: {$e->getMessage()}"
            ]);
        }

        return $model->load('service');
    }

    /***
     * Verifica el estado del cliente
     * @param int $clientId
     * @return void
     */
    private function validateClientStatus(int $clientId): void
    {
        $client = ClientModel::query()->select('status_id')->findOrFail($clientId);
        $status = CommonStatus::from($client->status_id);

        if ($status->isInactive()) {
            throw ValidationException::withMessages([
                'client' => "Este cliente debe estar activo para crear el servicio."
            ]);
        }
    }

    /***
     * @param int $clientId
     * @param array $inputs
     * @return ServiceModel
     * @throws \RouterOS\Exceptions\ClientException
     * @throws \RouterOS\Exceptions\ConfigException
     */
    private function checkPreviousService(int $clientId, array $inputs): ServiceModel
    {
        $service = ServiceModel::query()
            ->where([
                ['client_id', $clientId],
                ['status_id', CommonStatus::INACTIVE->value]
            ])
            ->oldest()
            ->first();
        if ($service) {
            $oldNode = $this->getNode($service->node_id);
            $oldServer = $oldNode->auth_server->toArray();
            $credentials = $this->checkCredentials($service->id);
            $nodeChanged = $service->node_id !== (int)$inputs['node'];
            $profileChanged = $credentials->internet_profile_id !== (int)$inputs['profile'];

            if ($nodeChanged) $this->changeNode($oldServer, $inputs, $credentials);

            if ($profileChanged) $this->changeProfile($credentials, $inputs);

            if (!$nodeChanged && !$profileChanged) $this->enableUser($credentials, (int)$inputs['node']);

            try {
                DB::transaction(function () use ($service, $inputs) {
                    $service->update([
                        'status_id' => CommonStatus::ACTIVE->value,
                        'installation_date' => Carbon::today(),
                        'node_id' => $inputs['node'],
                        'equipment_id' => $inputs['equipment'],
                        'latitude' => $inputs['latitude'],
                        'longitude' => $inputs['longitude'],
                        'state_id' => $inputs['state'],
                        'municipality_id' => $inputs['municipality'],
                        'district_id' => $inputs['district'],
                        'address' => $inputs['address'],
                        'technician_id' => $inputs['technician'],
                        'comments' => $inputs['comments'] ?? null,
                    ]);
                });
            } catch (Throwable $e) {
                throw ValidationException::withMessages([
                    'service' => "No se pudo actualizar el servicio: {$e->getMessage()}"
                ]);
            }

            return $service->refresh();
        }
        return $this->createService($inputs);
    }

    /***
     * @param array $params
     * @return ServiceModel
     */
    private function createService(array $params): ServiceModel
    {
        $dto = new ServiceDTO(
            client_id: (int)$params['client'],
            code: null,
            name: null,
            node_id: (int)$params['node'],
            equipment_id: (int)$params['equipment'],
            installation_date: Carbon::today(),
            technician_id: (int)$params['technician'],
            latitude: $params['latitude'],
            longitude: $params['longitude'],
            state_id: (int)$params['state'],
            municipality_id: (int)$params['municipality'],
            district_id: (int)$params['district'],
            address: $params['address'],
            separate_billing: CommonStatus::ACTIVE->value,
            status_id: CommonStatus::ACTIVE->value,
            comments: $params['comments'] ?? null,
        );
        $service = ServiceModel::query()->create($dto->toArray());
        $this->createCredentials($service, $params);
        return $service->refresh();
    }

    /***
     * @param ServiceModel $service
     * @param array $params
     * @return void
     */
    private function createCredentials(ServiceModel $service, array $params): void
    {
        $node = $this->getNode($service->node_id);
        $server = $node->auth_server->toArray();
        $profile = $this->getProfile((int)$params['profile'])->toArray();
        $client = $this->getClient((int)$params['client']);
        $user = $this->generateUser($node->prefix, $node->id);
        $password = $this->generateSecret($client);
        $comment = "{$client->name} {$client->surname}";

        $dto = new ServiceInternetDTO(
            internet_profile_id: (int)$params['profile'],
            service_id: $service->id,
            user: $user,
            secret: $password,
            status_id: CommonStatus::ACTIVE->value,
        );

        ServiceInternetModel::query()->create($dto->toArray());

        $this->mikrotikInternetService->createUser(
            server: $server,
            profile: $profile,
            username: $user,
            password: $password,
            comment: $comment
        );
    }

    /***
     * @param int $nodeId
     * @return NodeModel
     */
    private
    function getNode(int $nodeId): NodeModel
    {
        return NodeModel::query()->with('auth_server')->findOrFail($nodeId);
    }

    /***
     * @param int $service
     * @return ServiceInternetModel
     */
    private
    function checkCredentials(int $service): ServiceInternetModel
    {
        return ServiceInternetModel::query()->where('service_id', $service)->firstOrFail();
    }

    /***
     * @param array $oldServer
     * @param array $inputs
     * @param ServiceInternetModel $credentials
     * @return void
     */
    private function changeNode(array $oldServer, array $inputs, ServiceInternetModel $credentials): void
    {
        //  Eliminando credenciales de servidor anterior
        $this->mikrotikInternetService->deleteUser($oldServer, $credentials->user);

        //  Obteniendo datos necesarios
        $client = $this->getClient((int)$inputs['client']);
        $node = $this->getNode((int)$inputs['node']);
        $server = $node->auth_server->toArray();
        $user = $this->generateUser($node->prefix, $node->id);
        $password = $this->generateSecret($client);
        $comment = "{$client->name} {$client->surname}";
        $profile = $this->getProfile((int)$inputs['profile'])->toArray();

        try {
            DB::transaction(function () use ($credentials, $inputs, $server, $user, $password, $profile, $comment) {
                //  Actualizando credenciales
                $credentials->update([
                    'internet_profile_id' => (int)$inputs['profile'],
                    'user' => $user,
                    'secret' => $password,
                    'status_id' => CommonStatus::ACTIVE->value
                ]);

                //  Crear credenciales en nuevo server
                $this->mikrotikInternetService->createUser($server, $profile, $user, $password, $comment);
            });
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'credentials' => "No se pudieron actualizar las credenciales: {$e->getMessage()}"
            ]);
        }

    }

    /***
     * @param ServiceInternetModel $credentials
     * @param array $inputs
     * @return void
     */
    private function changeProfile(ServiceInternetModel $credentials, array $inputs): void
    {
        // Actualizando credenciales en la base de datos
        $credentials->update([
            'internet_profile_id' => (int)$inputs['profile'],
            'status_id' => CommonStatus::ACTIVE->value
        ]);

        $node = $this->getNode((int)$inputs['node']);
        $server = $node->auth_server->toArray();
        $profile = $this->getProfile((int)$inputs['profile'])->toArray();

        //  Habilitando usuario en servidor
        $this->mikrotikInternetService->updateUser($server, $credentials->user, [
            'profile' => $profile['mk_profile'],
            'disabled' => 'no'
        ]);
    }

    /***
     * @param ServiceInternetModel $credentials
     * @param int $nodeId
     * @return void
     * @throws \RouterOS\Exceptions\ClientException
     * @throws \RouterOS\Exceptions\ConfigException
     */
    private function enableUser(ServiceInternetModel $credentials, int $nodeId): void
    {
        $node = $this->getNode((int)$nodeId);
        $server = $node->auth_server->toArray();

        try {
            DB::transaction(function () use ($credentials, $server) {
                $credentials->update(['status_id' => CommonStatus::ACTIVE->value]);
            });
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'credentials' => "No fue posible activar credenciales: {$e->getMessage()}"
            ]);
        }

        $this->mikrotikInternetService->enableUser($server, $credentials->user);
    }

    /***
     * @param int $id
     * @return ClientModel
     */
    private function getClient(int $id): ClientModel
    {
        return ClientModel::query()->select(['name', 'surname'])->findOrFail($id);
    }

    /***
     * @param int $profileId
     * @return InternetModel
     */
    public function getProfile(int $profileId): InternetModel
    {
        return InternetModel::query()->findOrFail($profileId);
    }

    /***
     * @param string $prefix
     * @param int $nodeId
     * @return string
     */
    private function generateUser(string $prefix, int $nodeId): string
    {
        $prefix = 'NetPlus' . $prefix;
        $count = ServiceModel::query()->where('node_id', $nodeId)->withTrashed()->count();
        return sprintf("%s%05d", $prefix, $count);
    }

    /***
     * @param object $client
     * @return string
     */
    private function generateSecret(object $client): string
    {
        $firstName = explode(' ', trim($client->name))[0];
        $firstSurname = explode(' ', trim($client->surname))[0];
        $name = $firstName . '_' . $firstSurname;
        return transliterator_transliterate('Any-Latin; Latin-ASCII', $name);
    }

    /***
     * @param SupportModel $model
     * @param int $serviceId
     * @param array $params
     * @return void
     */
    private function updateSupport(SupportModel $model, int $serviceId, array $params): void
    {
        $model->update([
            'service_id' => $serviceId,
            'comments' => $params['comments'] ?? $model->comments,
        ]);
    }
}
