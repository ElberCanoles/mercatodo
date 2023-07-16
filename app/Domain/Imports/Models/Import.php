<?php

namespace App\Domain\Imports\Models;

use App\Domain\Imports\Presenters\ImportPresenter;
use Carbon\Carbon;
use Database\Factories\ImportFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $module
 * @property string $path
 * @property array $summary
 * @property array $errors
 * @property Carbon $created_at
 *
 * @method static Import create(array $attributes = [])
 */
class Import extends Model
{
    use HasFactory;

    protected $fillable = [
        'module',
        'path',
        'summary',
        'errors'
    ];

    protected $casts = [
        'summary' => 'array',
        'errors' => 'array'
    ];

    protected static function newFactory(): Factory
    {
        return ImportFactory::new();
    }

    public function present(): ImportPresenter
    {
        $presenter = ImportPresenter::getInstance();
        $presenter->setImport($this);
        return $presenter;
    }

}
