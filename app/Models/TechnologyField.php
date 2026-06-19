<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'code', 'color', 'order'])]
class TechnologyField extends Model
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

    public function technologies(): HasMany
    {
        return $this->hasMany(Technology::class);
    }
}
