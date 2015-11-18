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
                       ->orderBy('due')
                       ->with('label', 'assignee')
                       ->get();
    }
    
    /**
     * Returns all active user tasks
     */
    public function mine() {
        $user = auth()->user();
        
        return $user->tasks()
                    ->getQuery()
                    ->orderBy('due')
                    ->with('label', 'assignee')
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
        
        if (! empty($user_id = $request->input('user_id')) ) {
            $user = \App\User::findOrFail($user_id);
            $task->assignee()->associate($user);
        }
        
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
        $task->load('label', 'assignee', 'activity');
        
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
        if ( empty($user_id = $request->input('user_id')) ) {
            $task->assignee()->dissociate();
        } else {
            $user = \App\User::findOrFail($user_id);
            $task->assignee()->associate($user);
        }
        
        if ( empty($label_id = $request->input('label_id')) ) {
            $task->label()->dissociate();
        } else {
            $label = \App\ProjectLabel::findOrFail($label_id);
            $task->label()->associate($label);
        }
        
        $task->update($request->all());
        
        return $this->show($project, $task);
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
        
        return $task;
    }
    
    /**
     * 
     * @param Project $project
     * @param Task $task
     * @return Collecion
     */
    public function activity(Project $project, Task $task)
    {
        return $task->activity()
                    ->getQuery()
                    ->with('user')
                    ->get();
    }
    
    /**
     * 
     * @param Project $project
     * @param Task $task
     */
    public function comment(Request $request, Project $project, Task $task)
    {
        $comment = new \App\Activity($request->all() + ['type' => 'comment']);
        
        $comment->user()->associate(auth()->user());
        $comment->resource()->associate($task);
        $comment->save();
        
        return $comment;
    }
}