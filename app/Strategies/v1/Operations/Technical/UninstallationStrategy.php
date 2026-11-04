<?php

namespace App\Strategies\v1\Operations\Technical;

use App\Enums\v1\General\CommonStatus;
use App\Enums\v1\Supports\SupportStatus;
use App\Models\Clients\ClientModel;
use App\Models\Infrastructure\Network\NodeModel;
use App\Models\Services\ServiceInternetModel;
use App\Models\Services\ServiceModel;
use App\Models\Services\ServiceUninstallationModel;
use App\Models\Supports\SupportModel;
use App\Services\v1\network\MikrotikInternetService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class UninstallationStrategy extends BaseSupportStrategy
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
        try {
            DB::transaction(function () use ($model, $params) {
                $this->ensureExistingService($model);
                $this->ensureServiceStatus($model);
                $this->deactivateCredentials($model);
                $this->deactivateService($model);
                $this->deactivateClient($model);
            });

        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'support' => "Error al procesar la desinstalación: {$e->getMessage()}",
            ]);
        }

        return $model;
    }

    /***
     * @param SupportModel $model
     * @return void
     * @throws \RouterOS\Exceptions\ClientException
     * @throws \RouterOS\Exceptions\ConfigException
     */
    private function deactivateCredentials(SupportModel $model): void
    {
        $service = ServiceModel::query()->findOrFail($model->service_id);
        $credentials = ServiceInternetModel::query()
            ->where('service_id', $service->id)
            ->first();
        $node = NodeModel::query()->with('auth_server')->findOrFail($service->node_id);
        $server = $node->auth_server->toArray();

        ServiceUninstallationModel::query()
            ->create([
                'service_id' => $service->id,
                'internet_profile_id' => $credentials->internet_profile_id,
                'uninstallation_date' => Carbon::now()->toDateString(),
            ]);

        $this->mikrotikInternetService->disableUser($server, $credentials->user);

        if (!$credentials) {
            throw ValidationException::withMessages([
                'service' => "No se encontraron credenciales asociadas a este servicio.",
            ]);
        }

        $credentials->update(['status_id' => CommonStatus::INACTIVE->value]);
//        return $credentials->refresh();
    }

    /***
     * @param SupportModel $model
     * @return void
     */
    private function deactivateService(SupportModel $model): void
    {
        $service = ServiceModel::query()->find($model->service_id);

        if (!$service) {
            throw ValidationException::withMessages([
                'service' => "El servicio asociado no existe.",
            ]);
        }
        $service->update(['status_id' => CommonStatus::INACTIVE->value]);
    }

    /***
     * @param SupportModel $model
     * @return void
     */
    private function deactivateClient(SupportModel $model): void
    {
        $serviceAmount = ServiceModel::query()
            ->where([
                ['client_id', $model->client_id],
                ['status_id', CommonStatus::ACTIVE->value],
            ])
            ->count();

        if ($serviceAmount === 0) {
            $client = ClientModel::query()->find($model->client_id);

            if (!$client) {
                throw ValidationException::withMessages([
                    'client' => "El cliente asociado no existe.",
                ]);
            }

            $client->update(['status_id' => CommonStatus::INACTIVE->value]);
        }
    }
}
