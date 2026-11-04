<?php

namespace App\Http\Requests\v1\Management\Clients;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\client\ClientDTO;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
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
            'name' => 'required|string|between:2,100',
            'surname' => 'required|string|between:2,100',
            'gender' => 'required|integer',
//            'birthdate' => ['nullable', 'date', 'before_or_equal:today', Rule::date()->beforeOrEqual(today()->subYears(18))],
            'birthdate' => [
                'nullable',
                'date',
                'before_or_equal:' . today()->subYears(18)->format('Y-m-d'),
                'after_or_equal:' . today()->subYears(120)->format('Y-m-d')
            ],
            'marital' => 'required|integer',
            'branch' => 'required|integer',
            'type' => 'required|integer',
            'profession' => 'nullable|string|between:3,100',
            'country' => 'required|integer',
            'document' => 'required|integer',
            'entity' => 'required|boolean',
            'status' => 'required|boolean',
//            'comment' => 'nullable|string|between:10,1000',
            'comment' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombres es un campo obligatorio',
            'name.string' => 'Formato no válido',
            'name.between' => 'Campo nombres debe contener entre :min y :max caracteres',
            'surname.required' => 'Apellidos es un campo obligatorio',
            'surname.string' => 'Formato no válido',
            'surname.between' => 'Campo apellidos debe contener entre :min y :max caracteres',
            'gender.required' => 'Campo género es obligatorio',
            'gender.integer' => 'Formato no valido',
            'birthdate.date' => 'Formato no válido para la fecha de nacimiento',
            'birthdate.before_or_equal' => 'No es mayor de edad',
            'birthdate.after_or_equal' => 'La edad excede 120 años',
            'marital.required' => 'Estado civil es un campo obligatorio',
            'marital.integer' => 'Formato no valido',
            'branch.required' => 'Sucursal es un campo obligatorio',
            'branch.integer' => 'Formato no valido',
            'type.required' => 'Tipo de cliente es un campo obligatorio',
            'type.integer' => 'Formato no valido',
            'profession.string' => 'Formato no valido',
            'profession.between' => 'Profesión debe contener entre :min y :max caracteres',
            'country.required' => 'País es un campo obligatorio',
            'country.integer' => 'Formato no valido',
            'document.required' => 'Documento a emitir es un campo obligatorio',
            'document.integer' => 'Formato no valido',
            'entity.required' => 'Persona jurídica es un campo obligatorio',
            'entity.boolean' => 'Formato no valido',
            'status.required' => 'Estado es un campo obligatorio',
            'status.boolean' => 'Formato no valido',
            'comment.string' => 'Formato no valido para comentario',
            'comment.between' => 'El campo comentario debe contener entre :min y :max caracteres',
        ];
    }

    public function toDTO(): ClientDTO
    {
        return new ClientDTO(
            name: $this->input('name'),
            surname: $this->input('surname'),
            gender_id: $this->input('gender'),
            birthdate: Carbon::parse($this->input('birthdate')),
            marital_status_id: $this->input('marital'),
            branch_id: $this->input('branch'),
            client_type_id: $this->input('type'),
            profession: $this->input('profession'),
            country_id: $this->input('country'),
            document_type_id: $this->input('document'),
            legal_entity: $this->input('entity'),
            status_id: $this->input('status'),
            comments: $this->input('comment') ?? null,
        );
    }
}
