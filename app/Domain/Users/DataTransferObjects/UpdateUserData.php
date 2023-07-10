<?php declare(strict_types=1);

namespace App\Domain\Users\DataTransferObjects;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateUserData
{
    public function __construct(
        public string  $name,
        public string  $lastName,
        public string  $email,
        public ?string $status
    )
    {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            name: Str::ucfirst(Str::lower($request->input(key: 'name'))),
            lastName: Str::ucfirst(Str::lower($request->input(key: 'last_name'))),
            email: Str::lower($request->input(key: 'email')),
            status: $request->input(key: 'status') ?? null
        );
    }
}
