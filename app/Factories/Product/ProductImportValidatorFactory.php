<?php

declare(strict_types=1);

namespace App\Factories\Product;

use Illuminate\Validation\Rule;
use App\Enums\Product\ProductStatus;
use Illuminate\Support\Facades\Validator;

class ProductImportValidatorFactory
{
    public function make(array $data): \Illuminate\Validation\Validator
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:100', Rule::unique(table: 'products')->ignore($data['id'])],
            'price' => ['required', 'numeric', 'min:1', 'max:99999999'],
            'stock' => ['required', 'integer', 'min:0', 'max:9999999'],
            'status' => ['required', Rule::in(ProductStatus::asArray())],
            'description' => ['required', 'string', 'max:500'],
        ], [
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre no puede contener más de 100 caracteres',
            'name.unique' => 'Ya existe un producto registrado con este nombre',

            'price.required' => 'El precio es requerido',
            'price.numeric' => 'Debe ingresar un valor númerico',
            'price.min' => 'El precio debe ser al menos 1',
            'price.max' => 'El precio no debe ser mayor que 99999999',

            'stock.required' => 'El stock es requerido',
            'stock.integer' => 'Debe ingresar un número entero',
            'stock.min' => 'El stock debe ser al menos 0',
            'stock.max' => 'El stock no debe ser mayor que 9999999',

            'status.required' => 'El estado no es valido',
            'status.in' => 'El estado no es valido',

            'description.required' => 'La descripción es requerida',
            'description.max' => 'La descripción no puede contener más de 500 caracteres',
        ]);
    }
}
