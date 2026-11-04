<?php

namespace App\Http\Requests\v1\Management\Clients;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\client\DocumentDTO;
use Illuminate\Validation\Rule;

class DocumentRequest extends FormRequest
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
            'client' => 'required|integer',
            'type' => ['required', 'integer', Rule::unique('clients_documents', 'document_type_id')
                ->where(function ($query) {
                    $query->where('client_id', $this->input('client'));
                })
                ->ignore($this->route('id'))
            ],
            'number' => ['required', 'string', Rule::unique('clients_documents', 'number')->ignore($this->route('id'))],
            'expiration' => ['required', 'date', 'after_or_equal:' . today()->format('Y-m-d')],
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'client.required' => 'ID de cliente no definido.',
            'client.integer' => 'Formato inválido.',
            'type.required' => 'Tipo de documento no definido.',
            'type.integer' => 'Formato inválido para tipo de documento.',
            'type.unique' => 'Este cliente ya tiene este tipo de documento registrado.',
            'number.required' => 'Número de documento es un campo obligatorio.',
            'number.string' => 'Formato incorrecto para número de documento.',
            'number.unique' => 'Este documento ya ha sido registrado.',
            'expiration.required' => 'Fecha de vencimiento es un campo obligatorio.',
            'expiration.date' => 'Formato incorrecto para fecha de vencimiento.',
            'expiration.after_or_equal' => 'La fecha no puede ser menor o igual que la fecha actual.',
            'status.required' => 'Estado es un campo obligatorio',
            'status.boolean' => 'Formato no valido',
        ];
    }

    public function toDTO(): DocumentDTO
    {
        return new DocumentDTO(
            client_id: $this->input('client'),
            document_type_id: $this->input('type'),
            number: $this->input('number'),
            expiration_date: Carbon::parse($this->input('expiration')),
            status_id: $this->input('status'),
        );
    }
}
