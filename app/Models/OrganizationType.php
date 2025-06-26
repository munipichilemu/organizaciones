<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $name
 * @property string $description
 * @method static insert(array[] $array)
 */
class OrganizationType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];
}
