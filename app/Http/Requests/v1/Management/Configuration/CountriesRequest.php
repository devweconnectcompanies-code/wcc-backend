<?php

namespace App\Http\Requests\v1\Management\Configuration;

use Illuminate\Foundation\Http\FormRequest;

class CountriesRequest extends FormRequest
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
        $rules = [
            'esName' => 'required|string',
            'enName' => 'required|string',
            'iso2' => 'required|string|size:2|unique:config_countries,iso_2',
            'iso3' => 'required|string|size:3|unique:config_countries,iso_3',
            'prefix' => 'required|numeric|digits_between:1,4|unique:config_countries,phone_prefix',
            'status' => 'required|boolean',
        ];
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $id = $this->route('id') ?? $this->input('id');
            $rules['iso2'] = 'required|string|size:2|unique:config_countries,iso_2,' . $id;
            $rules['iso3'] = "required|string|size:3|unique:config_countries,iso_3,{$id}";
            $rules['prefix'] = "required|numeric|digits_between:1,4|unique:config_countries,phone_prefix,{$id}";
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'esName.required' => 'Nombre en español es requerido',
            'esName.string' => 'El nombre debe ser un texto',
            'enName.required' => 'Nombre en inglés es requerido',
            'iso2.required' => 'Formato ISO 2 es requerido',
            'iso2.string' => 'El formato debe ser un texto',
            'iso2.size' => 'El formato debe tener :size caracteres',
            'iso2.unique' => 'El código ISO 2 ya existe',
            'iso3.required' => 'Formato ISO 3 es requerido',
            'iso3.string' => 'El formato debe ser un texto',
            'iso3.size' => 'El formato debe tener :size caracteres',
            'iso3.unique' => 'Este código ISO 3 ya existe',
            'prefix.required' => 'El prefijo telefónico es requerido',
            'prefix.numeric' => 'El prefijo debe ser un numero',
            'prefix.digits_between' => 'El prefijo debe tener :min y :max caracteres',
            'prefix.unique' => 'Este prefijo ya existe',
            'status.required' => 'El estado es requerido',
            'status.boolean' => 'El estado debe ser un booleano',
        ];
    }
}
