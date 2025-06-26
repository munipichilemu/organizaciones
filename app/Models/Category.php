<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $name
 * @property string $description
 * @property string $icon
 * @method static insert(array[] $array)
 */
class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'icon',
    ];
}
