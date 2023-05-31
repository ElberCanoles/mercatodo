<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'regex:/^[a-zA-Z\sáÁéÉíÍóÓúÚüÜñÑ]+$/u', 'max:40'],
            'last_name' => ['required', 'string', 'regex:/^[a-zA-Z\sáÁéÉíÍóÓúÚüÜñÑ]+$/u', 'max:40'],
            'email' => ['required', 'string', 'email', 'max:80'],
            'document_type' => ['required'],
            'document_number' => ['required', 'integer', 'max:9999999999'],
            'cell_phone' => ['required', 'max:15'],
            'department' => ['required', 'string', 'max:40'],
            'city' => ['required', 'string', 'max:40'],
            'address' => ['required', 'string', 'max:100']
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
            'name.regex' => 'El nombre no es valido',
            'name.max' => 'El nombre no puede contener mas de 40 caracteres',

            'last_name.required' => 'El apellido es requerido',
            'last_name.string' => 'El apellido debe ser una cadena de texto',
            'last_name.regex' => 'El apellido no es valido',
            'last_name.max' => 'El apellido no puede contener mas de 40 caracteres',

            'email.required' => 'El email es requerido',
            'email.string' => 'El email debe ser una cadena de texto',
            'email.email' => 'El email ingresado no es valido',
            'email.max' => 'El email no puede contener mas de 80 caracteres',

            'document_type.required' => 'El tipo de documento es requerido',

            'document_number.required' => 'El número de documento es requerido',
            'document_number.integer' => 'Debe ingresar un número',
            'document_number.max' => 'El número de documento no puede contener mas de 10 caracteres',

            'cell_phone.required' => 'El celular es requerido',
            'cell_phone.max' => 'El celular no puede contener mas de 15 caracteres',

            'department.required' => 'El departamento es requerido',
            'department.string' => 'El departamento debe ser una cadena de texto',
            'department.max' => 'El departamento no puede contener mas de 40 caracteres',

            'city.required' => 'La ciudad es requerida',
            'city.string' => 'La ciudad debe ser una cadena de texto',
            'city.max' => 'La ciudad no puede contener mas de 40 caracteres',

            'address.required' => 'La dirección es requerida',
            'address.string' => 'La dirección debe ser una cadena de texto',
            'address.max' => 'La dirección no puede contener mas de 40 caracteres',
        ];
    }
}
