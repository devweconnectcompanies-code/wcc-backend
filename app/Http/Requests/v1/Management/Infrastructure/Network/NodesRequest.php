<?php

namespace App\Http\Requests\v1\Management\Infrastructure\Network;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\infrastructure\network\NodeDTO;

class NodesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|between:3,60',
            'prefix' => 'required|string|between:2,3',
            'server' => 'required|numeric',
            'lat' => 'required|decimal:6,8',
            'lng' => 'required|decimal:6,8',
            'state' => 'required|numeric',
            'municipality' => 'required|numeric',
            'district' => 'required|numeric',
            'address' => 'required|between:10,250',
            'nc' => 'required|size:12',
            'owner' => 'required|between:3,100',
            'comments' => 'nullable',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es un campo obligatorio.',
            'name.between' => 'Nombre debe tener entre 3 y 60 caracteres.',

            'prefix.required' => 'Prefijo es un campo obligatorio.',
            'prefix.string' => 'Formato inválido para prefijo.',
            'prefix.between' => 'El prefijo debe tener entre 2 y 3 caracteres.',

            'lat.required' => 'Latitud es un campo obligatorio.',
            'lat.decimal' => 'Debe contener entre 6 y 8 decimales.',

            'lng.required' => 'Longitud es un campo obligatorio.',
            'lng.decimal' => 'Debe contener entre 6 y 8 decimales.',

            'address.required' => 'Dirección es un campo obligatorio.',
            'address.between' => 'Debe tener entre 10 y 250 caracteres.',

            'nc.required' => 'NC es un campo obligatorio.',
            'nc.size' => 'Debe contener 12 caracteres.',

            'owner.required' => 'Propietario es un campo obligatorio.',
            'owner.between' => 'Debe tener entre 3 y 100 caracteres.',
        ];
    }

    public function toDTO(): NodeDTO
    {
        return new NodeDTO(
            name: $this->input('name'),
            prefix: $this->input('prefix'),
            server_id: $this->input('server'),
            latitude: $this->input('lat'),
            longitude: $this->input('lng'),
            state_id: $this->input('state'),
            municipality_id: $this->input('municipality'),
            district_id: $this->input('district'),
            address: $this->input('address'),
            nc: $this->input('nc'),
            nc_owner: $this->input('owner'),
            comments: $this->input('comments') ?? null,
            status_id: $this->input('status'),
        );
    }
}
