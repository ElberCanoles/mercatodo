<?php

declare(strict_types=1);

namespace App\Services\Utilities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SlugeableService
{

    const SALT_LENGTH = 10;

    /**
     * Get unique slug by model
     *
     * @param string $input
     * @param Model $model
     * @param string $columName
     * @return string
     */
    public function getUniqueSlugByEloquentModel(string $input, Model $model, string $columName): string
    {
        $safeSlug = $this->getSafeSlug($input);

        while ($model::where($columName, $safeSlug)->exists()) {
            $safeSlug = $this->getSafeSlug($input);
        }

        return $safeSlug;
    }


    /**
     * Get safe hash slug
     *
     * @param string $input
     * @return string
     */
    private function getSafeSlug(string $input): string
    {

        $preKeyword = $this->getSafeHash($this::SALT_LENGTH);

        $postKeyword = $this->getSafeHash($this::SALT_LENGTH);

        $baseSlug = "$preKeyword $input $postKeyword";

        return Str::slug($baseSlug);
    }


    /**
     * Get safe random hash with openssl
     *
     * @param integer $length
     * @return string
     */
    private function getSafeHash(int $length): string
    {
        return bin2hex(openssl_random_pseudo_bytes(($length - ($length % 2)) / 2));
    }
}
