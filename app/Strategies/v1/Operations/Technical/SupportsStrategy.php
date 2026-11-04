<?php

namespace App\Strategies\v1\Operations\Technical;

use App\Enums\v1\Supports\SupportStatus;
use App\Models\Supports\SupportModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class SupportsStrategy extends BaseSupportStrategy
{
    /*****
     * @throws ValidationException
     *****/
    public function handle(SupportModel $model, array $params): SupportModel
    {
        $status = SupportStatus::tryFrom((int)$params['status']);
        if (!$status || !$status->isFinalized()) return $model;

        try {
            DB::transaction(function () use ($model, $params, $status) {
                $this->ensureExistingService($model);
                $this->ensureServiceStatus($model);

                if (isset($params['solution']))
                    $model->solution = $params['solution'];

                if (isset($params['comments']))
                    $model->comments = $params['comments'];

                if ($status->isFinalized())
                    $model->closed_at = Carbon::now();

                $model->save();
            });
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'support' => "Error al procesar el soporte: {$e->getMessage()}",
            ]);
        }

        return $model->refresh();
    }
}
