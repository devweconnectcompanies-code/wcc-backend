<?php

namespace App\Http\Requests\v1\Management\Supports;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SurveyRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'support_id' => 'required|integer|exists:supports,id',
            'overall_rate' => 'required|integer|between:0,5',
            'attention_rate' => 'required|integer|between:0,5',
            'solution_rate' => 'required|integer|between:0,5',
            'punctuality_rate' => 'required|integer|between:0,5',
            'recommendation_rate' => 'required|integer|between:0,5',
            'resolved' => 'required|boolean',
            'comment' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'support_id.required' => 'El id de soporte es obligatorio.',
            'support_id.integer' => 'Formato inválido.',
            'support_id.exists' => 'El soporte seleccionado no existe.',

            'overall_rate.required' => 'Nota general es un campo obligatorio.',
            'overall_rate.integer' => 'Formato inválido.',
            'overall_rate.between' => 'La calificación debe ser entre 0 y 5',

            'attention_rate.required' => 'Este es un campo obligatorio.',
            'attention_rate.integer' => 'Formato inválido.',
            'attention_rate.between' => 'La calificación debe ser entre 0 y 5',

            'solution_rate.required' => 'Este es un campo obligatorio.',
            'solution_rate.integer' => 'Formato inválido.',
            'solution_rate.between' => 'La calificación debe ser entre 0 y 5',

            'punctuality_rate.required' => 'Este es un campo obligatorio.',
            'punctuality_rate.integer' => 'Formato inválido.',
            'punctuality_rate.between' => 'La calificación debe ser entre 0 y 5',

            'recommendation_rate.required' => 'Este es un campo obligatorio.',
            'recommendation_rate.integer' => 'Formato inválido.',
            'recommendation_rate.between' => 'La calificación debe ser entre 0 y 5',

            'resolved.required' => 'Este es un campo obligatorio.',
            'resolved.boolean' => 'Formato inválido.',

            'comment.string' => 'Formato incorrecto.',
            'comment.max' => 'Número de caracteres excedido.',
        ];
    }
}
