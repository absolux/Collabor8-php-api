<?php

/**
 * Description of LabelsControllerTest
 *
 * @author absolux
 */
class LabelsControllerTest extends \TestCase {
    
    
    /** @var  */
    protected $project;
    
    
    public function setUp() {
        parent::setUp();
        
        $this->_userLoginIn();
        
        $labels = factory(\App\ProjectLabel::class, 2)->make();
        
        $this->project = factory(\App\Project::class)->create();
        $this->project->labels()->saveMany($labels);
    }
    
    function testAddNewProjectLabelAction() {
        $this->post('/projects/1/labels', ['name' => 'foo'])
             ->seeStatusCode(200)
             ->shouldReturnJson(['name' => 'foo'])
             ->assertCount(3, $this->project->labels);
    }
    
    function testUpdateProjectLabelAction() {
        $this->put('/projects/1/labels/1', ['name' => 'bar'])
             ->seeStatusCode(200)
             ->shouldReturnJson(['name' => 'bar']);
    }
    
    function testDeleteProjectLabelAction() {
        $this->delete('/projects/1/labels/2')->seeStatusCode(200);
        $this->assertCount(1, $this->project->labels);
    }
}
