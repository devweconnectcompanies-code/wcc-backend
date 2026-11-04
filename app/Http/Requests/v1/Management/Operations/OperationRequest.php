<?php

namespace App\Http\Requests\v1\Management\Operations;

use App\Enums\v1\Supports\SupportStatus;
use App\Enums\v1\Supports\SupportType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OperationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //  Campos requeridos
            'type' => [
                'required',
                'integer',
                'exists:supports_types,id',
                Rule::enum(SupportType::class)
            ],
            'client' => ['required', 'integer', 'exists:clients,id'],
            'branch' => ['required', 'integer', 'exists:config_branches,id'],
            'state' => ['required', 'integer', 'exists:config_states,id'],
            'municipality' => ['required', 'integer', 'exists:config_municipalities,id'],
            'district' => ['required', 'integer', 'exists:config_districts,id'],
            'status' => [
                'required',
                'integer',
                'exists:supports_status,id',
                Rule::enum(SupportStatus::class),
                Rule::notIn([SupportStatus::PENDING->value, SupportStatus::ASSIGNED->value])
            ],
            'description' => ['required', 'string'],
            'address' => ['required', 'string'],
            'latitude' => [
                Rule::requiredIf(fn() => !in_array((int)$this->input('status'), [
                    SupportStatus::CANCELLED->value,
                    SupportStatus::OBSERVED->value,
                ])),
                'regex:/^-?([0-8]?[0-9]|90)\.\d{6,}$/',
                'numeric',
                'between:-90,90',
            ],
            'longitude' => [
                Rule::requiredIf(fn() => !in_array((int)$this->input('status'), [
                    SupportStatus::CANCELLED->value,
                    SupportStatus::OBSERVED->value,
                ])),
                'regex:/^-?(1[0-7][0-9]|[0-9]?[0-9]|180)\.\d{6,}$/',
                'numeric',
                'between:-180,180'
            ],
            'node' => [
                Rule::requiredIf(fn() => !in_array((int)$this->input('status'), [
                    SupportStatus::CANCELLED->value,
                    SupportStatus::OBSERVED->value,
                ])),
                'integer',
                'exists:infrastructure_nodes,id'
            ],
            'equipment' => [
                Rule::requiredIf(fn() => !in_array((int)$this->input('status'), [
                    SupportStatus::CANCELLED->value,
                    SupportStatus::OBSERVED->value,
                ])),
                'integer',
                'exists:infrastructure_equipment,id'
            ],
            'profile' => ['required', 'integer', 'exists:management_internet_profiles,id'],
            'technician' => ['required', 'integer'],

            //  Campos condicionales
            'service' => ['nullable', 'integer', 'exists:services,id'],
            'solution' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator): void
    {
        $supportType = SupportType::tryFrom((int)$this->input('type'));
        $supportStatus = SupportStatus::tryFrom((int)$this->input('status'));

        if (!$supportType || !$supportStatus) return;

        //  Validando servicio según tipo soporte
        $validator->sometimes(
            ['service'],
            'required|integer|exists:services,id',
            function ($input) use ($supportType) {
                return $supportType->requiresService();
            }
        );

        //  Validando solución según estado
        $validator->sometimes(
            'solution',
            'required',
            function ($input) use ($supportStatus) {
                return $supportStatus->requiresSolution();
            }
        );

        //  Validando comentario según estado
//        $validator->sometimes(
//            'comments',
//            'required',
//            function ($input) use ($supportStatus) {
//                return $supportStatus->requiresComments();
//            }
//        );
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Tipo de soporte es un campo obligatorio.',
            'type.integer' => 'Formato inválido.',
            'type.exists' => 'El tipo de soporte no existe.',

            'client.required' => 'Cliente es un campo obligatorio.',
            'client.integer' => 'Formato inválido.',
            'client.exists' => 'El cliente seleccionado no existe.',

            'branch.required' => 'Sucursal es un campo obligatorio.',
            'branch.integer' => 'Formato inválido.',
            'branch.exists' => 'La sucursal seleccionada no existe.',

            'state.required' => 'Departamento es un campo obligatorio.',
            'state.integer' => 'Formato inválido.',
            'state.exists' => 'El departamento seleccionado no existe.',

            'municipality.required' => 'Municipio es un campo obligatorio.',
            'municipality.integer' => 'Formato inválido.',
            'municipality.exists' => 'El municipio seleccionado no existe.',

            'district.required' => 'Distrito es un campo obligatorio.',
            'district.integer' => 'Formato inválido.',
            'district.exists' => 'El distrito seleccionado no existe.',

            'status.required' => 'Estado de soporte es un campo obligatorio.',
            'status.integer' => 'Formato inválido.',
            'status.exists' => 'El estado seleccionado no existe.',
            'status.not_in' => 'Debes seleccionar un estado válido para esta operación.',

            'description.required' => 'Descripción es un campo obligatorio.',
            'description.string' => 'Solo se admiten letras.',

            'address.required' => 'Dirección es un campo obligatorio.',
            'address.string' => 'Solo se admiten letras.',

            'latitude.required' => 'Latitud es un campo obligatorio.',
            'latitude.regex' => 'Formato inválido para latitud.',
            'latitude.numeric' => 'Latitud debe ser un número.',
            'latitude.between' => 'Formato incorrecto para latitud.',

            'longitude.required' => 'Longitud es un campo obligatorio.',
            'longitude.regex' => 'Formato inválido para longitud.',
            'longitude.numeric' => 'Longitud debe ser un número.',
            'longitude.between' => 'Formato incorrecto para longitud.',

            'service.required' => 'Servicio es un campo obligatorio.',
            'service.integer' => 'Formato inválido.',
            'service.exists' => 'El servicio seleccionado no existe.',

            'solution.required' => 'Solución es un campo obligatorio.',
            'solution.string' => 'Solo se admiten letras.',

            'comments.required' => 'Debes ingresar observación.',
            'comments.string' => 'Solo se admiten letras.',

            'profile.required' => 'Perfil es un campo obligatorio.',
            'profile.integer' => 'Formato inválido.',
            'profile.exists' => 'El perfil seleccionado no existe.',

            'node.required' => 'Nodo es un campo obligatorio.',
            'node.integer' => 'Formato inválido.',
            'node.exists' => 'El nodo seleccionado no existe.',

            'equipment.required' => 'Equipo es un campo obligatorio.',
            'equipment.integer' => 'Formato inválido.',
            'equipment.exists' => 'El equipo seleccionado no existe.',
        ];
    }
}
