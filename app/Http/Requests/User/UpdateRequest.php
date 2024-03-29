<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Domain\Users\Enums\UserStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'status' => ['required', Rule::in(UserStatus::asArray())],
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

            'status.required' => 'El estado es requerido',
            'status.in' => 'El estado no es valido',
        ];
    }
}
