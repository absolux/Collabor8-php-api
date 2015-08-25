<?php

use App\Activity;
use App\User;

/**
 * Description of ActivityTest
 *
 * @author absolux
 */
class ActivityTest extends \TestCase {
    
    
    function testCreateDummyActivity() {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        
        $activity = factory(Activity::class)->create();
        
        $this->assertInstanceOf(User::class, $activity->user);
        $this->assertEquals($user->id, $activity->user->id);
    }
}
