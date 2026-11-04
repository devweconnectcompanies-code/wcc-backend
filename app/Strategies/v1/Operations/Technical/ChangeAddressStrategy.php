<?php

namespace App\Strategies\v1\Operations\Technical;

use App\Enums\v1\General\CommonStatus;
use App\Enums\v1\Supports\SupportStatus;
use App\Models\Clients\ClientModel;
use App\Models\Infrastructure\Network\NodeModel;
use App\Models\Management\Profiles\InternetModel;
use App\Models\Services\ServiceInternetModel;
use App\Models\Services\ServiceModel;
use App\Models\Supports\SupportModel;
use App\Services\v1\network\MikrotikInternetService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class ChangeAddressStrategy extends BaseSupportStrategy
{
    /***
     * @param MikrotikInternetService $mikrotikInternetService
     */
    public function __construct(private MikrotikInternetService $mikrotikInternetService)
    {

    }

    /*****
     * @param SupportModel $model
     * @param array $params
     * @return SupportModel
     */
    public function handle(SupportModel $model, array $params): SupportModel
    {
        $status = SupportStatus::tryFrom((int)$params['status']);

        if (!$status || !$status->isFinalized()) return $model;

        try {
            DB::transaction(function () use ($model, $params) {
                $this->ensureExistingService($model);
                $this->ensureServiceStatus($model);
                $this->updateServiceAddress($model->service_id, $params);
            });
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'support' => "Error al procesar cambio de domicilio. {$e->getMessage()}",
            ]);
        }

        return $model->refresh();
    }

    private function updateServiceAddress(int $serviceId, array $params): void
    {
        $service = $this->getService($serviceId);
        $credentials = $this->getCredentials($serviceId);
        $nodeChanged = $service->node_id !== (int)$params['node'];
        if ($nodeChanged) $this->migrateService($service, $credentials, $params);
        $this->updateAddress($service, $params);
    }

    /***
     * @param int $id
     * @return ServiceModel
     */
    private function getService(int $id): ServiceModel
    {
        return ServiceModel::query()->findOrFail($id);
    }

    /***
     * @param int $id
     * @return NodeModel
     */
    private function getNode(int $id): NodeModel
    {
        return NodeModel::query()->with('auth_server')->findOrFail($id);
    }

    /***
     * @param int $service
     * @return ServiceInternetModel
     */
    private function getCredentials(int $service): ServiceInternetModel
    {
        return ServiceInternetModel::query()->where('service_id', $service)->firstOrFail();
    }

    /***
     * @param ServiceModel $service
     * @param array $params
     * @return void
     */
    private function updateAddress(ServiceModel $service, array $params): void
    {
        $service->update([
            'node_id' => (int)$params['node'],
            'equipment_id' => (int)$params['equipment'],
            'latitude' => $params['latitude'],
            'longitude' => $params['longitude'],
            'state_id' => (int)$params['state'],
            'municipality_id' => (int)$params['municipality'],
            'district_id' => (int)$params['district'],
            'address' => $params['address'],
        ]);
    }

    /***
     * @param ServiceModel $service
     * @param ServiceInternetModel $credentials
     * @param array $params
     * @return void
     */
    private function migrateService(
        ServiceModel         $service,
        ServiceInternetModel $credentials,
        array                $params
    ): void
    {
        //  Borrando credenciales de antiguo Auth Server
        $oldNode = $this->getNode($service->node_id);
        $oldServer = $oldNode->auth_server->toArray();
        $this->mikrotikInternetService->deleteUser($oldServer, $credentials->user);

        //  Obteniendo datos necesarios
        $client = $this->getClient($service->client_id);
        $node = $this->getNode((int)$params['node']);
        $server = $node->auth_server->toArray();
        $user = $this->generateUser($node->prefix, $params['node']);
        $password = $this->generateSecret($client);
        $profile = $this->getProfile($params['profile'])->toArray();
        $comment = "{$client->name} {$client->surname}";

        //  Actualizando credenciales en la base de datos
        $credentials->update([
            'internet_profile_id' => (int)$params['profile'],
            'user' => $user,
            'secret' => $password,
            'status_id' => CommonStatus::ACTIVE->value,
        ]);

        //  Creando PPPoe Secret en Auth Server
        $this->mikrotikInternetService->createUser($server, $profile, $user, $password, $comment);
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
        $name = $firstName . ' ' . $firstSurname;
        $ascii = transliterator_transliterate('Any-Latin; Latin-ASCII', $name);
        return preg_replace('/[^A-Za-z0-9_]/', '_', $ascii);
    }

    /***
     * @param int $profileId
     * @return InternetModel
     */
    public function getProfile(int $profileId): InternetModel
    {
        return InternetModel::query()->findOrFail($profileId);
    }

}
