<?php

namespace App\Http\Controllers\v1\management\billing;

use App\DTOs\v1\management\billing\extensions\ExtensionDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Billing\ExtensionRequest;
use App\Http\Resources\v1\management\general\GeneralResource;
use App\Models\Billing\InvoiceExtensionModel;
use App\Services\v1\management\billing\ExtensionsService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExtensionController extends Controller
{
    /***
     * Retorna el listado de extensiones de una factura
     * @param Request $request
     * @param ExtensionsService $service
     * @return JsonResponse
     */
    public function invoiceExtensionList(Request $request, ExtensionsService $service): JsonResponse
    {
        $extensions = $service->invoiceExtensionData($request->invoice_id);
        return response()->json([
            'data' => new GeneralResource($extensions),
        ]);
    }

    /***
     * Asigna extensión a una factura
     * @param ExtensionRequest $request
     * @param ExtensionsService $service
     * @return JsonResponse
     */
    public function store(ExtensionRequest $request, ExtensionsService $service): JsonResponse
    {
        $DTO = $this->createDTO($request);
        $extension = $service->createExtension($DTO);

        return response()->json([
            'saved' => (bool)$extension,
            'extension' => new GeneralResource($extension),
        ]);
    }

    /***
     * Retorna los datos según id de extensión
     * @param int $id
     * @param ExtensionsService $service
     * @return JsonResponse
     */
    public function read(int $id, ExtensionsService $service): JsonResponse
    {
        $extension = $service->extensionData($id);

        return response()->json([
            'extension' => new GeneralResource($extension),
        ]);
    }

    /***
     * Actualiza extensión según id
     * @param ExtensionRequest $request
     * @param InvoiceExtensionModel $id
     * @param ExtensionsService $service
     * @return JsonResponse
     */
    public function update(
        ExtensionRequest      $request,
        InvoiceExtensionModel $id,
        ExtensionsService     $service
    ): JsonResponse
    {
        $dto = $this->createDTO($request);
        $extension = $service->updateExtension($id, $dto);

        return response()->json([
            'saved' => (bool)$extension,
            'extension' => new GeneralResource($extension),
        ]);
    }

    /***
     * Crea el DTO con los datos correspondientes
     * @param $data
     * @return ExtensionDTO
     */
    private function createDTO($data): ExtensionDTO
    {
        return new ExtensionDTO(
            invoiceId: $data['invoice'],
            previousDueDate: Carbon::createFromFormat('Y-m-d', $data['previous_due_date']),
            days: $data['days'],
            reason: $data['reason'],
            user_id: Auth::user()->id,
            status_id: $data['status'],
        );
    }
}
