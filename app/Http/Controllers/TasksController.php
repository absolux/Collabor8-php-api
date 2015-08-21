<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Project;
use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the project tasks with label and assigned user.
     *
     * @param Project $project
     * @return Response
     */
    public function index(Project $project)
    {
        return $project->tasks()
                       ->getQuery()
                       ->with('label', 'assigned')
                       ->get();
    }

    /**
     * Store a newly created task.
     *
     * @param Request $request
     * @param Project $project
     * @return Task
     */
    public function store(Request $request, Project $project)
    {
        $task = new Task($request->all());
        
        $project->tasks()->save($task);
        
        return $task;
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     * @param Task $task
     * @return Task
     */
    public function show(Project $project, Task $task)
    {
        $task->load('label', 'assigned', 'activity');
        
        return $task;
    }

    /**
     * Update the specified task
     *
     * @param Request $request
     * @param Project $project
     * @param Task $task
     * @return Task
     */
    public function update(Request $request, Project $project, Task $task)
    {
        if ( $request->has('user_id') ) {
            $user = \App\User::findOrFail($request->input('user_id'));
            $task->assigned()->associate($user);
        }
        
        if ( $request->has('label_id') ) {
            $label = \App\ProjectLabel::findOrFail($request->input('label_id'));
            $task->label()->associate($label);
        }
        
        $task->update($request->all());
        
        return $task;
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Project $project
     * @param Task $task
     */
    public function destroy(Project $project, Task $task)
    {
        $task->delete();
    }
}
