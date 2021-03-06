<?php

use App\Project;
use App\User;
use App\ProjectLabel;

/**
 * Description of ProjectTest
 *
 * @author absolux
 */
class ProjectTest extends \TestCase {
    
    
    /**
     * 
     * @return Project
     */
    function testCreateEmptyProject() {
        $this->_userLoginIn();
        $project = factory(Project::class)->create();
        
        $this->assertInstanceOf(Project::class, $project);
        
        // project activity 
        $this->assertEquals(1, $project->activity()->get()->count());
        $activity = $project->activity()->get()->get(0);
        $this->assertEquals('create', $activity->type);
        
        return $project;
    }
    
    /**
     * @depends testCreateEmptyProject
     * @param Project $project
     */
    function testCreateProjectWithManager(Project $project) {
        $user = factory(User::class)->make();
        
        $project->team()->save($user, ['role' => 'manager']);
        $this->assertEquals(1, $project->team()->get()->count());
        
        $manager = $project->team()->get()->get(0);
        $this->assertEquals('manager', $manager->pivot->role);
    }
    
    /**
     * @depends testCreateEmptyProject
     * @param Project $project
     */
    function testProjectUserAssignment(Project $project) {
        $user = factory(User::class)->create();
        
        // attaching
        $project->team()->attach($user);
        $this->assertFalse($project->team()->get()->isEmpty());
        
        $user = $project->team()->get()->get(0);
        $this->assertEquals('member', $user->pivot->role);
        
        // detaching
        $project->team()->detach($user);
        $this->assertTrue($project->team()->get()->isEmpty());
    }
    
    /**
     * @depends testCreateEmptyProject
     * @param Project $project
     */
    function testUpdateProjectName(Project $project) {
        $this->_userLoginIn();
        
        $old_name = $project->name;
        $project->update(['name' => "New project name"]);
        
        $this->assertNotEquals($old_name, $project->name);
        $this->assertEquals(1, $project->activity()->get()->count());
    }
    
    /**
     * @depends testCreateEmptyProject
     * @param Project $project
     */
    function testMakeProjectArchived(Project $project) {
        $this->_userLoginIn();
        
        $project->delete();
        $this->assertTrue($project->trashed());
        
        $project->restore();
        $this->assertFalse($project->trashed());
    }
    
    /**
     * @depends testCreateEmptyProject
     * @param Project $project
     */
    function testCreateProjectLabels(Project $project) {
        factory(ProjectLabel::class, 3)->make()->each(function($label) use($project) {
            $project->labels()->save($label);
        });
        
        $this->assertEquals(3, $project->labels->count());
    }
}
