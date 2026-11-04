<?php

namespace App\Http\Requests\v1\Management\Admin\Profiles;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\admin\profiles\InternetDTO;
use Carbon\Carbon;

class InternetRequest extends FormRequest
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
            'name' => 'required|string',
            'alias' => 'required|string',
            'description' => 'required|string|between:10,255',
            'mk_profile' => 'required|string',
            'debt_profile' => 'required|string',
            'net_value' => 'required|decimal:2,8',
            'iva' => 'required|decimal:2,8',
            'price' => 'required|decimal:2,8',
            'expiration' => 'required|date|after:today',
            'status' => 'required|integer|in:0,1',
            'iptv' => 'required|integer|in:0,1',
            'ftth' => 'required|integer|in:0,1',
            'stb' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es un campo obligatorio.',
            'name.string' => 'Formato inválido.',

            'alias.required' => 'Alias es un campo obligatorio.',
            'alias.string' => 'Formato incorrecto.',

            'description.required' => 'Descripción es un campo obligatorio.',
            'description.string' => 'Formato incorrecto.',
            'description.between' => 'Debe contener entre :min y :max caracteres.',

            'mk_profile.required' => 'Perfil Mikrotik es un campo obligatorio.',
            'mk_profile.string' => 'Formato incorrecto.',

            'net_value.required' => 'Valor neto es un campo obligatorio.',
            'net_value.decimal' => 'Debe tener entre :min y :max decimales.',

            'iva.required' => 'IVA es un campo obligatorio.',
            'iva.decimal' => 'Debe tener entre :min y :max decimales.',

            'price.required' => 'Precio es un campo obligatorio.',
            'price.decimal' => 'Debe tener entre :min y :max decimales.',

            'expiration.required' => 'Fecha de vencimiento es un campo obligatorio.',
            'expiration.date' => 'Formato incorrecto.',
            'expiration.after' => 'No puede ser menor a el día de ahora.',

            'status.required' => 'Estado es un campo obligatorio.',
            'status.integer' => 'Formato incorrecto.',
            'status.in' => 'Formato seleccionado inválido.',

            'iptv.required' => 'IPTV es un campo obligatorio.',
            'iptv.integer' => 'Formato incorrecto.',
            'iptv.in' => 'Formato seleccionado incorrecto.',

            'ftth.required' => 'FTTH es un campo obligatorio.',
            'ftth.integer' => 'Formato incorrecto.',
            'ftth.in' => 'Formato seleccionado incorrecto.',

            'stb.integer' => 'Cantidad de STB debe ser un número entero.',
        ];
    }

    public function toDTO(): InternetDTO
    {
        return new InternetDTO(
            name: $this->input('name'),
            alias: $this->input('alias'),
            description: $this->input('description'),
            mk_profile: $this->input('mk_profile'),
            debt_profile: $this->input('debt_profile') ?? null,
            net_value: $this->input('net_value'),
            iva: $this->input('iva'),
            price: $this->input('price'),
            expiration_date: Carbon::parse($this->input('expiration')),
            status_id: $this->input('status'),
            iptv: $this->input('iptv'),
            ftth: $this->input('ftth'),
            allowed_stb: $this->input('stb') ?? 0,
        );
    }
}
