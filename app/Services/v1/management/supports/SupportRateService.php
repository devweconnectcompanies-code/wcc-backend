<?php

namespace App\Services\v1\management\supports;

use App\DTOs\v1\management\supports\RatingDTO;
use App\Models\Supports\SupportRatingModel;

class SupportRateService
{
    /**
     * Registra los datos para una encuesta nueva.
     *
     * @param RatingDTO $dto
     * @return SupportRatingModel
     */
    public function create(RatingDTO $dto): SupportRatingModel
    {
        return SupportRatingModel::query()->create($dto->toArray());
    }

    /**
     * Obtiene los datos de una encuesta determinada.
     *
     * @param int $support_id
     * @return SupportRatingModel|null
     */
    public function surveyData(int $support_id): ?SupportRatingModel
    {
        return SupportRatingModel::query()
            ->with('support.technician.user')
            ->where('support_id', $support_id)
            ->first();
    }

    /**
     * Actualiza los datos de una encuesta.
     *
     * @param SupportRatingModel $model
     * @param RatingDTO $dto
     * @return SupportRatingModel
     */
    public function update(SupportRatingModel $model, RatingDTO $dto): SupportRatingModel
    {
        $model->update($dto->toArray());
        return $model->refresh();
    }
}
