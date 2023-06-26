<?php

declare(strict_types=1);

namespace App\Http\Requests\Product;

use App\Enums\Product\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public const MAX_IMAGES = 5;

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
        $preloadedImagesCount = count($this->input('preloaded_images', []));
        $imagesMaxCount = $this::MAX_IMAGES - $preloadedImagesCount;

        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('products')->ignore(request()->product)],
            'price' => ['required', 'numeric', 'min:1', 'max:99999999'],
            'stock' => ['required', 'integer', 'min:0', 'max:9999999'],
            'status' => ['required', Rule::in(ProductStatus::asArray())],
            'description' => ['required', 'string', 'max:500'],
            'preloaded_images' => ['required_without:images', 'array', 'max:5'],
            'images' => ['required_without:preloaded_images', 'array', 'max:'.$imagesMaxCount],
            'images.*' => ['required','image','mimes:png,jpg,jpeg', 'max:5000'],
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

            'preloaded_images.required_without' => 'Si elimina todas las imagenes antiguas, debe adjuntar nuevas',
            'preloaded_images.max' => 'Máximo 5 imágenes',

            'images.required_without' => 'Debe agregar al menos una imagen',

            'images.array' => 'Debe adjuntar una lista de imagenes',
            'images.max' => 'Máximo 5 imágenes',

            'images.*.required' => 'El archivo en la posición: #:position de los nuevos adjuntos es requerido',
            'images.*.image' => 'El archivo en la posición: #:position de los nuevos adjuntos debe ser una imagen',
            'images.*.mimes' => 'El archivo en la posición: #:position de los nuevos adjuntos deber ser de tipo (png,jpg,jpeg)',
            'images.*.max' => 'El archivo en la posición: #:position de los nuevos adjuntos no debe pesar más de 5 MB'
        ];
    }
}
