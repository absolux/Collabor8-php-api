<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Project;
use App\ProjectLabel;

class LabelsController extends Controller
{
    
    /**
     * Dsiplay a listing of the project labels
     * 
     * @param Project $project
     * @return Response
     */
    public function index(Project $project)
    {
        $project->load('labels');
        
        return response()->json($project);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function store(Request $request, Project $project)
    {
        $label = new ProjectLabel($request->all());
        
        $project->labels()->save($label);
        
        return response()->json($label);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Project $project
     * @param ProjectLabel $label
     * @return Response
     */
    public function update(Request $request, Project $project, ProjectLabel $label)
    {
        $label->update($request->all());
        
        return response()->json($label);http://localhost:8000

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @param ProjectLabel $label
     * @return Response
     */
    public function destroy(Project $project, ProjectLabel $label)
    {
        $label->delete();
    }
}
