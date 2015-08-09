<?php

use App\Task;
use App\Project;
use App\User;

/**
 * Description of TaskTest
 *
 * @author absolux
 */
class TaskTest extends \TestCase {
    
    
    /** @var Task */
    protected $task;
    
    
    public function setUp() {
        parent::setUp();
        
        $this->_userLoginIn();
        
        $this->project = factory(Project::class)->create();
        
        $this->task = factory(Task::class)->make();
        $this->task->project()->associate($this->project);
        $this->task->save();
    }
    
    function testQuickTaskCreated() {
        $this->assertInstanceOf(Project::class, $this->task->project);
        $this->assertEquals(1, $this->task->activity->count());
        $this->assertEquals('create', $this->task->activity->get(0)->type);
    }
    
    function testSetTaskAssignedUser() {
        $user = factory(User::class)->create(['name' => "simo"]);
        
        $this->task->assigned()->associate($user);
        $this->task->save();
        
        $this->assertEquals('simo', $this->task->assigned->name);
        $this->assertEquals(2, $this->task->activity->count());
        $this->assertEquals('user_id', $this->task->activity->get(1)->type);
    }
    
    function testSetLabelToTask() {
        $label = factory(\App\ProjectLabel::class)->make(['name' => "foo"]);
        
        $this->project->labels()->save($label);
        $this->task->label()->associate($label)->save();
        
        $this->assertEquals('foo', $this->task->label->name);
        $this->assertEquals(2, $this->task->activity->count());
        $this->assertEquals($label->id, $this->task->activity->get(1)->note);
    }
}
