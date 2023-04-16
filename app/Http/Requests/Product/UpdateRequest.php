<?php

namespace App\Http\Requests\Product;

use App\Enums\Product\ProductStatus;
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:1', 'max:99999999'],
            'stock' => ['required', 'integer', 'min:0', 'max:9999999'],
            'status' => ['required', Rule::in(ProductStatus::asArray())],
            'description' => ['required', 'string', 'max:500'],
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
            'name.max' => 'El nombre no puede contener más de 100 caracteres',

            'price.required' => 'El precio es requerido',
            'price.numeric' => 'Debe ingresar un valor númerico',
            'price.min' => 'El precio debe ser al menos 1',
            'price.max' => 'El precio no debe ser mayor que 99999999',

            'stock.required' => 'El stock es requerido',
            'stock.integer' => 'Debe ingresar un número entero',
            'stock.min' => 'El stock debe ser al menos 0',
            'stock.max' => 'El stock no debe ser mayor que 9999999',

            'status.required' => 'El estado es requerido',
            'status.in' => 'El estado no es valido',

            'description.required' => 'La descripción es requerida',
            'description.max' => 'La descripción no puede contener más de 500 caracteres',
        ];
    }
}
