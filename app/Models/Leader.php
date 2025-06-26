<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laragear\Rut\HasRut;

class Leader extends Model
{
    use HasRut, HasUlids, SoftDeletes;

    protected $fillable = [
        'name',
        'rut',
        'address',
        'phone',
        'email',
    ];

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'leader_organization')
            ->withPivot('member_position_id')
            ->withTimestamps();
    }

    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(MemberPosition::class, 'leader_organization')
            ->withPivot('organization_id');
    }
}
