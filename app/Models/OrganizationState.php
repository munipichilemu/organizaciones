<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $name
 * @property array $color
 * @property string $icon
 * @method static insert(array[] $array)
 */
class OrganizationState extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'color',
        'icon',
    ];

    protected $casts = [
        'color' => 'array',
    ];
}
