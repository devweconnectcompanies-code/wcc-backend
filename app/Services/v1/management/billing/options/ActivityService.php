<?php

namespace App\Services\v1\management\billing\options;

use App\DTOs\v1\management\billing\options\ActivityDTO;
use App\Models\Billing\Options\ActivityModel;

class ActivityService
{
    public function createActivity(ActivityDTO $activityDTO): ActivityModel
    {
        return ActivityModel::create($activityDTO->toArray());
    }

    public function updateActivity(ActivityModel $model, ActivityDTO $activityDTO): ActivityModel
    {
        $model->update($activityDTO->toArray());
        return $model;
    }
}
