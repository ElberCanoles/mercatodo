<?php

declare(strict_types=1);

namespace App\Domain\Users\DataTransferObjects;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdateUserPasswordData
{
    public function __construct(public string $password)
    {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            password: Hash::make($request->input(key: 'password'))
        );
    }
}
