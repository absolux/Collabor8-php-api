<?php

use App\Project;

/**
 * Description of ProjectTest
 *
 * @author absolux
 */
class ProjectTest extends \TestCase {
    
    
    function testCreateProjectViaFactory() {
        $project = factory(Project::class)->make();
        
        $this->assertInstanceOf(Project::class, $project);
    }
}
