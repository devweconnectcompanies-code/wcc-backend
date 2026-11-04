<?php

namespace App\Http\Controllers\v1\management\Accounting\DTE\Options;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Accounting\Options\EventRequest;
use App\Http\Resources\v1\management\general\GeneralResource;
use App\Models\Accounting\Config\EventTypeModel;
use App\Services\v1\management\accounting\DTE\options\EventsService;
use App\Services\v1\management\DataViewerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Data grid de la tabla principal.
     *
     * @param Request $request
     * @param DataViewerService $dataViewerService
     * @return JsonResponse
     */
    public function data(Request $request, DataViewerService $dataViewerService): JsonResponse
    {
        $query = EventTypeModel::query();

        return $dataViewerService->handle($request, $query, [
            'status' => fn($q, $data) => $q->whereIn('status', $data),
        ]);
    }

    /**
     * Almacena eventos.
     *
     * @param EventRequest $request
     * @param EventsService $eventsService
     * @return JsonResponse
     */
    public function store(EventRequest $request, EventsService $eventsService): JsonResponse
    {
        $save = $eventsService->storeEvent($request->toDTO());

        return response()->json([
            'saved' => $save,
            'event' => new GeneralResource($save),
        ]);
    }

    /**
     * Retorna los datos de un evento basado en id.
     *
     * @param Request $request
     * @param EventsService $eventsService
     * @return JsonResponse
     */
    public function edit(Request $request, EventsService $eventsService): JsonResponse
    {
        $edit = $eventsService->editEvent($request->input('id'));

        return response()->json([
            'event' => new GeneralResource($edit),
        ]);
    }

    /**
     * Actualiza los datos de un evento.
     *
     * @param EventRequest $request
     * @param EventTypeModel $id
     * @param EventsService $eventsService
     * @return JsonResponse
     */
    public function update(EventRequest $request, EventTypeModel $id, EventsService $eventsService): JsonResponse
    {
        $update = $eventsService->updateEvent($id, $request->toDTO());

        return response()->json([
            'saved' => $update,
            'event' => new GeneralResource($update),
        ]);
    }
}
