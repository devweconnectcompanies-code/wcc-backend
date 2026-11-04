<?php

namespace App\Strategies\v1\Operations\Technical;

use App\Contracts\v1\Supports\ProcessSupportInterface;
use App\Enums\v1\General\CommonStatus;
use App\Enums\v1\Supports\SupportStatus;
use App\Models\Services\ServiceModel;
use App\Models\Supports\SupportModel;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

abstract class BaseSupportStrategy implements ProcessSupportInterface
{

    public function process(SupportModel $model, array $params): SupportModel
    {
        $model = $this->handle($model, $params);
        $this->applyStatusLogic($model, $params);
        return $model->refresh();
    }

    abstract protected function handle(SupportModel $model, array $params): SupportModel;

    protected function applyStatusLogic(SupportModel $model, array $params): void
    {
        $statusId = (int)$params['status'];
        $status = SupportStatus::from($statusId);
        $now = Carbon::now();

        if ($this->isFinalStatus($model->status_id)) {
            if (isset($params['comments'])) {
                $model->comments = $params['comments'];
                $model->save();
            }
            return;
        }

        $model->status_id = $statusId;
        $model->comments = $params['comments'] ?? $model->comments;

        if ($status === SupportStatus::ENDED) {
            $model->closed_at = $now;
            $creation = Carbon::parse($model->creation_date);
            $model->resolution_time = $creation->diffInMicroseconds($now);
            $model->breached_sla = $creation->diffInHours($now) > 72;

            if (isset($params['solution'])) {
                $model->solution = $params['solution'];
            }
        } elseif (in_array($status, [SupportStatus::CANCELLED, SupportStatus::OBSERVED])) {
            $creation = Carbon::parse($model->creation_date);
            $model->resolution_time = $creation->diffInMicroseconds($now);
            $model->breached_sla = $creation->diffInHours($now) > 72;
        }
        $model->save();
    }

    protected function isFinalStatus(int $statusId): bool
    {
        return in_array($statusId, [
            SupportStatus::ENDED->value,
            SupportStatus::CANCELLED->value,
            SupportStatus::OBSERVED->value,
        ]);
    }

    /****
     * @throws ValidationException
     ****/
    protected function ensureExistingService(SupportModel $model): void
    {
        if (!$model->service_id) {
            throw ValidationException::withMessages([
                'support' => 'El servicio no existe.',
            ]);
        }
    }

    /*****
     * @throws ValidationException
     *****/
    protected function ensureServiceStatus(SupportModel $model): void
    {
        $service = ServiceModel::query()->findOrFail($model->service_id);
        $status = CommonStatus::from($service->status_id);

        if ($status->isInactive()) {
            throw ValidationException::withMessages([
                'service' => 'Este servicio se encuentra inactivo.',
            ]);
        }
    }
}
