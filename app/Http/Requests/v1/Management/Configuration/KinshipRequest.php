<?php

namespace App\Http\Requests\v1\Management\Configuration;

use App\DTOs\v1\management\configuration\clients\KinshipDTO;
use Illuminate\Foundation\Http\FormRequest;

class KinshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|between:3,50',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es requerido.',
            'name.between' => 'El nombre debe tener entre :min y :max caracteres.',
        ];
    }

    public function toDTO(): KinshipDTO
    {
        return new KinshipDTO(
            name: $this->input('name'),
            status_id: $this->input('status'),
        );
    }
}
