<?php

namespace App\Http\Controllers\v1\management\supports;

use App\DTOs\v1\management\supports\RatingDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Supports\SurveyRequest;
use App\Http\Resources\v1\management\general\GeneralResource;
use App\Models\Supports\SupportRatingModel;
use App\Services\v1\management\supports\SupportRateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SurveyController extends Controller
{
    /**
     * Almacena nueva encuesta.
     *
     * @param SurveyRequest $request
     * @param SupportRateService $service
     * @return JsonResponse
     */
    public function store(SurveyRequest $request, SupportRateService $service): JsonResponse
    {
        $dto = new RatingDTO(
            support_id: $request->support_id,
            overall_rate: $request->overall_rate,
            attention_rate: $request->attention_rate,
            solution_rate: $request->solution_rate,
            punctuality_rate: $request->punctuality_rate,
            recommendation_rate: $request->recommendation_rate,
            resolved: $request->resolved,
            comment: $request->comment,
            survey_datetime: Carbon::now(),
        );

        $query = $service->create($dto);

        return response()->json([
            'saved' => (bool)$query,
            'survey' => new GeneralResource($query),
        ]);
    }

    /**
     * Retorna los datos de una encuesta determinada.
     *
     * @param Request $request
     * @param SupportRateService $service
     * @return JsonResponse
     */
    public function read(Request $request, SupportRateService $service): JsonResponse
    {
        return response()->json(['survey' => $service->surveyData($request->support_id)]);
    }

    /**
     * Actualiza los datos de una encuesta determinada.
     *
     * @param SurveyRequest $request
     * @param SupportRatingModel $id
     * @param SupportRateService $service
     * @return JsonResponse
     */
    public function update(SurveyRequest $request, SupportRatingModel $id, SupportRateService $service): JsonResponse
    {
        $dto = new RatingDTO(
            support_id: $request->support_id,
            overall_rate: $request->overall_rate,
            attention_rate: $request->attention_rate,
            solution_rate: $request->solution_rate,
            punctuality_rate: $request->punctuality_rate,
            recommendation_rate: $request->recommendation_rate,
            resolved: $request->resolved,
            comment: $request->comment,
            survey_datetime: $id->survey_datetime,
        );

        $query = $service->update($id, $dto);

        return response()->json([
            'saved' => (bool)$query,
            'survey' => new GeneralResource($query),
        ]);
    }
}
