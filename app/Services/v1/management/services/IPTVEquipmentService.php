<?php

namespace App\Services\v1\management\services;

use App\DTOs\v1\management\services\ServiceIptvEquipmentDTO;
use App\Enums\v1\General\CommonStatus;
use App\Enums\v1\General\InfrastructureStatus;
use App\Models\Infrastructure\Equipment\InventoryLogModel;
use App\Models\Infrastructure\Equipment\InventoryModel;
use App\Models\Management\Profiles\InternetModel;
use App\Models\Services\ServiceIptvEquipmentModel;
use App\Models\Services\ServiceModel;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class IPTVEquipmentService
{
    public function list(int $serviceId): Collection
    {
        return ServiceIptvEquipmentModel::query()
            ->with([
                'equipment.type:id,name',
                'equipment.brand:id,name',
                'equipment.model:id,name',
            ])
            ->where('service_id', $serviceId)
            ->get();
    }

    public function assign(ServiceIptvEquipmentDTO $DTO): ServiceIptvEquipmentModel
    {
        $hasIPTV = $this->hasIptv($DTO->service_id);
        $client = $this->client($DTO->service_id);

        if (!$hasIPTV) {
            throw ValidationException::withMessages([
                'service' => ["Este servicio no posee IPTV"]
            ]);
        }

        $equipment = InventoryModel::query()
            ->where([
                ['id', $DTO->equipment_id],
                ['status_id', InfrastructureStatus::ON_ROUTE->value],
            ])
            ->first();

        if (!$equipment) {
            throw ValidationException::withMessages([
                'equipment' => ["Este dispositivo no existe o no está disponible."]
            ]);
        }

        $equipment->update(['status_id' => InfrastructureStatus::OPERATIVE->value]);

        $message = 'Equipo asignado a ';
        $message .= $client;
        $message .= ' en el servicio ID: ' . $DTO->service_id;
        $message .= ' mediante el módulo servicios';

        InventoryLogModel::query()->create([
            'equipment_id' => $equipment->id,
            'user_id' => Auth::user()->id,
            'technician_id' => null,
            'execution_date' => Carbon::today(),
            'service_id' => $DTO->service_id,
            'status_id' => InfrastructureStatus::OPERATIVE->value,
            'description' => $message,
        ]);

        return ServiceIptvEquipmentModel::query()->create($DTO->toArray());
    }

    public function read(int $serviceId)
    {
        return ServiceIptvEquipmentModel::query()->with('equipment')->find($serviceId);
    }

    public function update(ServiceIptvEquipmentModel $model, ServiceIptvEquipmentDTO $DTO): ServiceIptvEquipmentModel
    {
        /***
         * Verificar que el correo sea el mismo, de lo contrario a log.
         * Verificar que sean las mismas contraseñas, si no, a log.
         ***/
        $client = $this->client($DTO->service_id);
        $hasIPTV = $this->hasIptv($DTO->service_id);
        $message = null;

        if (!$hasIPTV) {
            throw ValidationException::withMessages([
                'service' => ["Este servicio no posee IPTV"]
            ]);
        }

        if ((int)$model->service_id !== (int)$DTO->service_id) {
            $message = "Equipo cambio del servicio con ID: {$model->service_id} al servicio con ID: {$DTO->service_id}";
        }

        if ($model->email !== $DTO->email) {
            $message = "El correo ha sido cambiado de {$model->email} a {$DTO->email}. Servicio: {$model->service_id}";
        }

        if ($model->email_password !== $DTO->email_password) {
            $message = "La contraseña del correo ha sido cambiada de {$model->email_password} a {$DTO->email_password}. Servicio: {$model->service_id}";
        }

        if ($model->iptv_password !== $DTO->iptv_password) {
            $message = "La contraseña del servicio iptv ha sido cambiada de {$model->iptv_password} a {$DTO->iptv_password}. Servicio: {$model->service_id}";
        }

        if ($message) {
            InventoryLogModel::query()
                ->create([
                    'equipment_id' => $DTO->equipment_id,
                    'user_id' => Auth::user()->id,
                    'technician_id' => null,
                    'execution_date' => Carbon::today(),
                    'service_id' => $DTO->service_id,
                    'status_id' => InfrastructureStatus::OPERATIVE->value,
                    'description' => $message,
                ]);
        }

        $model->update($DTO->toArray());
        return $model->refresh();
    }

    public function delete(int $id): bool
    {
        $iptv = ServiceIptvEquipmentModel::query()
            ->with(['equipment', 'service.client'])
            ->find($id);

        if (!$iptv) {
            throw ValidationException::withMessages([
                'equipment' => ["Este dispositivo EQM no existe"]
            ]);
        }

        $emailUsageCount = ServiceIptvEquipmentModel::query()
            ->where([
                ['email', $iptv->email],
                ['email_correlative', $iptv->email_correlative],
                ['status_id', CommonStatus::ACTIVE->value]
            ])
            ->whereNull('deleted_at')
            ->where('id', '!=', $id)
            ->count();

        $message = "Equipo desvinculado del servicio ID: {$iptv->service_id}";
        $message .= ' correspondiente al cliente: ';
        $message .= "{$iptv->service?->client?->name} {$iptv->service?->client?->surname}";

        if ($emailUsageCount === 0) {
            $message .= " - Correo liberado completamente y disponible para reutilización: {$iptv->email}";
        } else {
            $message .= " - Correo {$iptv->email} ahora tiene {$emailUsageCount} dispositivo(s) activo(s)";
        }

        $device = InventoryModel::query()->find($iptv->equipment_id);

        InventoryLogModel::query()->create([
            'equipment_id' => $iptv->equipment_id,
            'user_id' => Auth::user()->id,
            'technician_id' => null,
            'execution_date' => Carbon::today(),
            'service_id' => $iptv->service_id,
            'status_id' => InfrastructureStatus::ON_ROUTE->value,
            'description' => $message,
        ]);

        $device->update(['status_id' => InfrastructureStatus::ON_ROUTE->value]);

        return $iptv->delete();
    }

    private function client(int $serviceId): string
    {
        $service = ServiceModel::query()
            ->with(['client'])
            ->find($serviceId);

        return $service->client?->name . ' ' . $service->client?->surname;

    }

    private function hasIptv(int $serviceId): bool
    {
        $service = ServiceModel::query()
            ->with(['internet.profile'])
            ->find($serviceId);
        return $service->internet?->profile?->iptv;
    }

    public function internet_plan(int $serviceId): InternetModel
    {
        $service = ServiceModel::query()
            ->with('internet.profile')
            ->findOrFail($serviceId);

        return InternetModel::query()->find($service->internet?->profile?->id);
    }

    public function getSuggestedEmail(): array
    {
        $maxDevicesPerEmail = 3;
        $allEmails = ServiceIptvEquipmentModel::query()
            ->withTrashed()
            ->select(['email', 'email_password', 'deleted_at'])
            ->get();

        $usageByEmail = $allEmails->groupBy('email')->map(function ($items, $email) use ($maxDevicesPerEmail) {
            $activeCount = $items->whereNull('deleted_at')->count();
            return [
                'email' => $email,
                'email_password' => $items->first()->email_password,
                'active_count' => $activeCount,
                'delete_count' => $items->whereNotNull('deleted_at')->count(),
                'available_slots' => $maxDevicesPerEmail - $activeCount,
                'correlative' => $this->extractCorrelative($email),
            ];
        });

        $availableEmails = $usageByEmail->filter(function ($data) {
            return $data['available_slots'] > 0;
        })->sortBy('correlative');

        $selected = $availableEmails->first();

        if (!$selected) {
            return $this->generateNewEmail();
        }

        return [
            'email' => $selected['email'],
            'email_password' => $selected['email_password'],
        ];
    }

    protected function extractCorrelative(string $email): int
    {
        if (preg_match('/netplusprofile(\d+)@gmail\.com/', $email, $matches)) {
            return (int)$matches[1];
        }

        return PHP_INT_MAX;
    }

    protected function generateNewEmail(): array
    {
        $maxCorrelative = ServiceIptvEquipmentModel::query()
            ->withTrashed()
            ->select('email')
            ->get()
            ->map(function ($item) {
                return $this->extractCorrelative($item->email);
            })
            ->max();
        $nextCorrelative = $maxCorrelative !== null ? $maxCorrelative + 1 : 1;

        return [
            'email' => "netplusprofile{$nextCorrelative}@gmail.com",
            'email_password' => 'Netplus#@1',
        ];
    }
}
