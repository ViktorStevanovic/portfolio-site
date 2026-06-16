<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable('user_id', 'job_title', 'tagline', 'bio', 'photo', 'cv_path', 'cv_downloads', 'github_url', 'linkedin_url', 'email_public')]
class Profile extends Model
{
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
