<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Project;
use App\Http\Requests\CreateProjectForm;

class ProjectsController extends Controller {
    
    
    /**
     * Display a list of user assigned projects
     *
     * @return Response
     */
    public function index() {
        return response()->json(auth()->user()->projects);
    }
    
    /**
     * display all projects 
     * 
     * @return Response
     */
    public function all() {
        return response()->json(Project::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateProjectForm  $request
     * @return Response
     */
    public function store(CreateProjectForm $request) {
        $project = Project::create($request->all());
        
        // attach to the current user
        $project->team()->attach(auth()->user(), ['role' => 'manager']);
        
        return $this->show($project);
    }

    /**
     * Display the specified resource.
     *
     * @param  Project  $project
     * @return Response
     */
    public function show(Project $project) {
        $project->load('labels', 'team', 'activity');
        
        return response()->json($project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Project  $project
     * @return Response
     */
    public function update(Request $request, Project $project) {
        $project->update($request->all());
        
        return $this->show($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Project  $project
     * @return Response
     */
    public function destroy(Project $project) {
        $project->forceDelete();
    }
    
    /**
     * soft delete a project
     * 
     * @param Project $project
     */
    public function archive(Project $project) {
        $project->delete();
        
        return $this->show($project);
    }
    
    /**
     * Restore the soft deleted project
     * 
     * @param Project $project
     */
    public function restore(Project $project) {
        $project->restore();
        
        return $this->show($project);
    }
}