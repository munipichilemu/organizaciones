<?php

namespace App\Models;

use App\InformationSource;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laragear\Rut\HasRut;

/**
 * @property OrganizationType $type
 * @property Category $category
 * @property OrganizationState $state
 */
class Organization extends Model
{
    use HasRut, HasUlids, SoftDeletes;

    protected $fillable = [
        'registration_id',
        'name',
        'rut',
        'information_source',
        'address',
        'organization_type_id',
        'category_id',
        'organization_state_id',
        'registered_at',
        'confirmed_at',
    ];

    protected $casts = [
        'information_source' => InformationSource::class,
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(OrganizationType::class, 'organization_type_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(OrganizationState::class, 'organization_state_id');
    }

    public function leaders(): BelongsToMany
    {
        return $this->belongsToMany(Leader::class, 'leader_organization')
            ->withPivot('member_position_id')
            ->withTimestamps();
    }
}
