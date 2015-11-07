<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Project;
use App\User;

class TeamsController extends Controller
{
    /**
     * Display a listing of project team.
     *
     * @return Response
     */
    public function index(Project $project)
    {
        $project->load('team');
        
        return response()->json($project);
    }

    /**
     * Attach a user as a project team member.
     *
     * @param Request $request
     * @param Project $project
     * @return User
     */
    public function store(Request $request, Project $project)
    {
        $email = $request->input('email');
        $user = User::Where('email', $email)->first();
        
        if (! $user ) {
            // Should send an invitation to join the project team
            // The new user must register him self to access the app
            // For now, only an empty response is sent instead
            return;
        }
        
        $project->team()->detach($user); // to prevent multiple assignments
        $project->team()->attach($user);
        
        return $user;
    }

    /**
     * Detach a user member
     * 
     * @param Project $project
     * @param User $user
     */
    public function destroy(Project $project, User $user)
    {
        $project->team()->detach($user);
        
        return $user;
    }

    /**
     * Update the role of the selected member
     *
     * @param  Request $request
     * @param  Project $project
     * @param User $user
     */
    public function update(Request $request, Project $project, User $user)
    {
        $role = $request->input('role', 'member');
        
        $project->team()->updateExistingPivot($user->id, ['role' => $role]);
        
        return $user;
    }
}
