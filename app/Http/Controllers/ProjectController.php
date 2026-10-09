<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Project;
use Illuminate\View\View;

/*
 * routes/web.php:
 *
 *   use App\Http\Controllers\ProjectController;
 *
 *   Route::get('/projects/{project:code}', [ProjectController::class, 'show'])
 *       ->name('projects.show');
 */
class ProjectController extends Controller
{
    public function show(Project $project): View
    {
        abort_unless($project->is_visible, 404);

        $project->load(['technologies.technologyField', 'images']);

        // Neighbours in the same order the home page uses.
        $visible = Project::query()
            ->where('is_visible', true)
            ->whereNotNull('code')
            ->orderBy('order')
            ->get(['id', 'name', 'code', 'order']);

        $position = $visible->search(fn (Project $p) => $p->is($project));

        return view('projects.show', [
            'project'         => $project,
            // Load $profile exactly as your home page controller does.
            'profile'         => Profile::with('user')->first(),
            'previousProject' => $position > 0 ? $visible[$position - 1] : null,
            'nextProject'     => $position !== false ? $visible->get($position + 1) : null,
        ]);
    }
}