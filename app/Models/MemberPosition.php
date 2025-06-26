<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static find($state)
 */
class MemberPosition extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug',
        'title',
        'order',
    ];
}
