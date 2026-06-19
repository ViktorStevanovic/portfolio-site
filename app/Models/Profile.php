<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable('user_id', 'job_title', 'tagline', 'bio', 'photo', 'cv_path', 'cv_downloads', 'is_maintenance', 'github_url', 'linkedin_url', 'twitter_url', 'website_url', 'email_public')]
class Profile extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
