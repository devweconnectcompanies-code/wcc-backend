<?php

namespace App\Http\Controllers\v1\management\clients;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Clients\EmailRequest;
use App\Http\Resources\v1\management\clients\EmailResource;
use App\Models\Clients\EmailModel;
use App\Services\v1\management\client\EmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailsController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        return response()->json([
            'response' => EmailModel::query()
                ->where('client_id', $request->get('clientID'))
                ->get()
        ]);
    }

    public function store(EmailRequest $request, EmailService $service): JsonResponse
    {
        $email = $service->createEmail($request->toDTO());

        return response()->json([
            'saved' => (bool)$email,
            'email' => new EmailResource($email),
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json([
            'email' => EmailModel::query()->findOrFail($request->get('id')),
        ]);
    }

    public function update(EmailRequest $request, EmailModel $id, EmailService $service): JsonResponse
    {
        $email = $service->updateEmail($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$email,
            'email' => new EmailResource($email),
        ]);
    }
}
