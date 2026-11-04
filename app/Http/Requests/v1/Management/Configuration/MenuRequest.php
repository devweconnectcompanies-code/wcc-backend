<?php

namespace App\Http\Requests\v1\Management\Configuration;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\configuration\menu\MenuDTO;

class MenuRequest extends FormRequest
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
            'name' => 'required',
            'url' => ['required', 'regex:/^(\/[a-zA-Z\/-]+|#)$/'],
            'icon' => ['required', 'regex:/^[a-z_-]+$/'],
            'parent' => 'nullable|integer',
            'order' => 'nullable|integer',
            'status' => 'required|boolean',
            'slug' => 'required|regex:/^[a-zA-Z0-9.]+$/'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Este campo es requerido.',
            'url.required' => 'Este campo es requerido.',
            'url.regex' => 'Este campo no tiene un formato correcto.',
            'icon.required' => 'Este campo es requerido.',
            'icon.regex' => 'Este campo no tiene un formato correcto.',
            'parent.integer' => 'Este campo no tiene un formato correcto.',
            'order.integer' => 'Este campo no tiene un formato correcto.',
            'status.required' => 'Este campo es requerido.',
            'status.boolean' => 'Este campo no tiene un formato correcto.',
            'slug.required' => 'Este campo es requerido.',
            'slug.regex' => 'Este campo no tiene un formato correcto.',
        ];
    }

    public function toDTO(): MenuDTO
    {
        $parent = $this->input('parent') === 0 || $this->input('parent') === null ? null : $this->input('parent');
        return new MenuDTO(
            name: $this->input('name'),
            url: $this->input('url'),
            icon: $this->input('icon'),
            parent_id: $parent,
            order: $this->input('order'),
            status_id: $this->input('status'),
            slug: $this->input('slug')
        );
    }
}
