<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'code', 'icon', 'proficiency', 'order', 'technology_field_id'])]
class Technology extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function casts()
    {
        return [
        ];
    }

    public function technologyField(): BelongsTo
    {
        return $this->belongsTo(TechnologyField::class);
    }

    public function experiences(): BelongsToMany
    {
        return $this->belongsToMany(Experience::class);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }
}
