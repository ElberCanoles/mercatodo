<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutData
{
    public function __construct(
        public string $name,
        public string $lastName,
        public string $documentType,
        public string $documentNumber,
        public string $email,
        public string $cellPhone,
        public string $department,
        public string $city,
        public string $address,
        public string $paymentMethod,
        public ?string $orderId
    ) {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new static(
            name: $request->input(key: 'name'),
            lastName: $request->input(key: 'last_name'),
            documentType: $request->input(key: 'document_type'),
            documentNumber: $request->input(key: 'document_number'),
            email: $request->input(key: 'email'),
            cellPhone: $request->input(key: 'cell_phone'),
            department: $request->input(key: 'department'),
            city: $request->input(key: 'city'),
            address: $request->input(key: 'address'),
            paymentMethod: $request->input(key: 'provider'),
            orderId: $request->input(key: 'order')
        );
    }
}
