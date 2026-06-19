<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable('name', 'code', 'short_description', 'description', 'status', 'repository_url', 'demo_url', 'order', 'is_visible', 'is_featured')]
class Project extends Model
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

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('order');
    }
}
