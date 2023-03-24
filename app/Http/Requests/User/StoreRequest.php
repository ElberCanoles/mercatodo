<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', "max:255"],
            'last_name' => ['required', 'string', "max:255"],
            'email' => ['required', 'string', 'email', "max:255", 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => "El nombre no puede contener mas de 255 caracteres",

            'last_name.required' => 'El apellido es requerido',
            'last_name.string' => 'El apellido debe ser una cadena de texto',
            'last_name.max' => "El apellido no puede contener mas de 255 caracteres",

            'email.required' => 'El email es requerido',
            'email.string' => 'El email debe ser una cadena de texto',
            'email.email' => 'El email ingresado no es valido',
            'email.max' => "El email no puede contener mas de 255 caracteres",
            'email.unique' => 'El email ya ha sido registrado',

            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe contener minimo 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ];
    }
}
