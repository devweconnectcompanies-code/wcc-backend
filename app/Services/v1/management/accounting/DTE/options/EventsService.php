<?php

namespace App\Services\v1\management\accounting\DTE\options;

use App\DTOs\v1\management\accounting\dte\events\EventDTO;
use App\Models\Accounting\Config\EventTypeModel;

class EventsService
{
    public function storeEvent(EventDTO $dto): EventTypeModel
    {
        return EventTypeModel::query()->create($dto->toArray());
    }

    public function editEvent(int $id): EventTypeModel
    {
        return EventTypeModel::query()->findOrFail($id);
    }

    public function updateEvent(EventTypeModel $eventTypeModel, EventDTO $dto): EventTypeModel
    {
        $eventTypeModel->update($dto->toArray());
        return $eventTypeModel->refresh();
    }
}
