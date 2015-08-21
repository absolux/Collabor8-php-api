<?php

use App\Project;
use App\Task;
use App\User;

/**
 * Description of TasksControllerTest
 *
 * @author absolux
 */
class TasksControllerTest extends \TestCase {
    
    
    public function setUp() {
        parent::setUp();
        
        $this->_userLoginIn();
        
        $project = factory(Project::class)->create();
        $tasks = factory(Task::class, 5)->make()->each(function(Task $t) use ($project) {
            $t->project()->associate($project);
            $t->assigned()->associate(auth()->user());
        });
        
        $project->tasks()->saveMany($tasks);
        
        $this->project = $project;
    }
    
    function testShowProjectTasks() {
        $this->visit('/projects/1/tasks')
             ->seeStatusCode(200)
             ->shouldReturnJson();
        
        $this->assertCount(5, $this->project->tasks);
    }
    
    function testCreateNewTask() {
        $name = "Lorem ipsum";
        
        $this->post('/projects/1/tasks', ['name' => $name])
             ->seeStatusCode(200)
             ->shouldReturnJson(['name' => $name]);
    }
    
    function testUpdateTaskDueDate() {
        $this->patch('/projects/1/tasks/3', ['due' => new Carbon\Carbon()])
             ->seeStatusCode(200);
    }
    
    function testChangeTaskAssignedUser() {
        $user = factory(User::class)->create();
        
        $this->patch('/projects/1/tasks/2', ['user_id' => $user->id])
             ->seeStatusCode(200)
             ->seeJson(['user_id' => (string) $user->id]);
    }
    
    function testSetUnknownUserAsTaskAssignee() {
        $this->put('/projects/1/tasks/2', ['user_id' => 404])
             ->seeStatusCode(500);
    }
}
