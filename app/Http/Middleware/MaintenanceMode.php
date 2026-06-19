<?php

namespace App\Http\Middleware;

use App\Models\Profile;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        $profile = Profile::first();

        if ($profile?->is_maintenance) {
            return response(view('maintenance', compact('profile')), 503);
        }

        return $next($request);
    }
}
