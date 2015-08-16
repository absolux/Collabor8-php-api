<?php

use App\Project;

/**
 * Description of ProjectsControllerTest
 *
 * @author absolux
 */
class ProjectsControllerTest extends \TestCase {
    
    
    public function setUp() {
        parent::setUp();
        
        $this->_userLoginIn();
        
        factory(Project::class, 3)->create()->each(function(Project $p) {
            $p->team()->attach(auth()->user(), ['role' => 'manager']);
        });
        
        factory(Project::class, 2)->create()->each(function(Project $p) {
            $p->team()->attach(factory(App\User::class)->create(), ['role' => 'manager']);
        });
    }
    
    function testLoggedInUserProjectsCount() {
        $this->visit('/projects')
             ->seeStatusCode(200)
             ->receiveJson(['role' => 'manager'])
             ->assertCount(3, $this->_parseJsonResponse());
    }
    
    function testAllProjectsCount() {
        $this->visit('/projects/all')
             ->seeStatusCode(200)
             ->assertCount(5, $this->_parseJsonResponse());
    }
    
    function testStoreProjectAction() {
        $this->post('/projects', ['name' => "Dummy project"])
             ->seeStatusCode(200)
             ->shouldReturnJson();
        
        $json = $this->_parseJsonResponse();
        
        $this->assertEquals("Dummy project", $json['name']);
        $this->assertCount(1, $json['team']);
        $this->assertCount(1, $json['activity']);
    }
    
    function testUpdateProjectAction() {
        $desc = "Lorem ipsum sin dolor.";
        
        $this->patch('/projects/1', ['desc' => $desc])
             ->seeStatusCode(200)
             ->shouldReturnJson();
        
        $json = $this->_parseJsonResponse();
        
        $this->assertEquals($desc, $json['desc']);
        $this->assertCount(2, $json['activity']); // create + update activities
    }
    
    function testDeleteProjectAction() {
        $this->delete('/projects/1')->seeStatusCode(200);
        
        $this->assertCount(4, Project::withTrashed()->get()); // definitely deleted
    }
    
    function testArchiveProjectAction() {
        $this->post('/projects/1/archive')->seeStatusCode(200)->shouldReturnJson();
        
        $json = $this->_parseJsonResponse();
        
        $this->assertCount(4, Project::all());
        $this->assertCount(2, $json['activity']); // create + update activities
        $this->assertNotNull($json['deleted_at']);
    }
    
    function testRestoreProjectAction() {
        Project::find(1)->delete();
        
        // FIXED fails because Project::find(id) wouldn't return a trashed project
        $this->post('/projects/1/restore')->seeStatusCode(200)->shouldReturnJson();
        
        $json = $this->_parseJsonResponse();
        
        $this->assertCount(5, Project::all());
        $this->assertNull($json['deleted_at']);
    }
    
    private function _parseJsonResponse() {
        return json_decode($this->response->content(), true);
    }
}
