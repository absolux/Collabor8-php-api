<?php

use App\Project;
use App\User;

/**
 * Description of TeamsControllerTest
 *
 * @author absolux
 */
class TeamsControllerTest extends \TestCase {
    
    
    public function setUp() {
        parent::setUp();
        
        $this->_userLoginIn();
        
        $project = factory(Project::class)->create();
        $project->team()->attach(auth()->user(), ['role' => 'manager']);
        $project->team()->attach(factory(User::class)->create());
        
        $this->project = $project;
    }
    
    function testShowProjectTeam() {
        $this->visit('/projects/1/team')
             ->seeStatusCode(200)
             ->shouldReturnJson();
    }
    
    function testAddUserToProjectTeam() {
        $email = 'foo@bar.com';
        $user = factory(User::class)->create(['email' => $email]);
        
        $this->post('/projects/1/team', ['email' => $email])
             ->seeStatusCode(200)
             ->shouldReturnJson();
        
        $json = $this->_parseJsonResponse();
        
        $this->assertEquals($user->id, $json['id']);
    }
    
    function testAddTheSameUserMultipleTimes() {
        $email = 'foo@bar.com';
        factory(User::class)->create(['email' => $email]);
        
        $this->post('/projects/1/team', ['email' => $email]);
        $this->post('/projects/1/team', ['email' => $email]);
        
        $team = Project::find(1)->team;
        $this->assertCount(3, $team);
    }
    
    function testDetachProjectMember() {
        $this->delete('/projects/1/team/2');
    }
    
    function testUpdateProjectMemberRole() {
        $this->put('/projects/1/team/2', ['role' => 'foo'])->seeStatusCode(200);
        
        $user = $this->project->team->get(1);
        $this->assertEquals('foo', $user->pivot->role);
    }
}
