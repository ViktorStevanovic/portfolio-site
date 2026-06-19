<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('route', 'method', 'ip', 'user_agent', 'referrer', 'created_at')]
class VisitLog extends Model
{
    const UPDATED_AT = null;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function casts()
    {
        return [
            'created_at' => 'datetime',
        ];
    }
}
