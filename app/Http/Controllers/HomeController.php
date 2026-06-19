<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $profile = Profile::with('user')->first();

        $experiences = Experience::with(['company', 'technologies.technologyField'])
            ->where('is_visible', true)
            ->orderByRaw('CASE WHEN end_date IS NULL THEN 0 ELSE 1 END')
            ->orderBy('start_date', 'desc')
            ->get();

        $projects = Project::with(['technologies.technologyField', 'images'])
            ->where('is_visible', true)
            ->orderBy('order')
            ->get();

        return view('home', compact('profile', 'experiences', 'projects'));
    }
}
