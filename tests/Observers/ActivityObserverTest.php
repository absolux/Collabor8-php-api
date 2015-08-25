<?php

/**
 * Description of ActivityObserverTest
 *
 * @author absolux
 */
class ActivityObserverTest extends \TestCase {
    
    /** @var Mockery\Mock */
    protected $mock;
    
    /** @var App\Observers\ActivityObserver */ 
    protected $observer;
    
    
    public function setUp() {
        parent::setUp();
        
        $this->observer = new App\Observers\ActivityObserver();
        
        $this->mock = Mockery::mock('Stub');
    }
    
    public function tearDown() {
        parent::tearDown();
        
        Mockery::close();
    }
    
    
    function testCreatedCallback() {
        $this->mock->shouldReceive('activity->create')->once()->andReturn(true);
        
        $this->assertTrue($this->observer->created($this->mock));
    }
    
    function testUpdatedCallback() {
        $data = ['name' => "foo", 'level' => "bar"];
        
        $this->mock->shouldReceive('activity->create')->times(count($data));
        $this->mock->shouldReceive('getDirty')->once()->andReturn($data);
        
        $this->observer->updated($this->mock);
    }
    
    function testSoftDeletedCallback() {
        $mock = Mockery::mock(ModelStub::class)->makePartial();
        $mock->shouldReceive('activity->create')->once()->andReturn('archived');
        
        $this->assertEquals("archived", $this->observer->deleted($mock));
    }
    
    function testRestoredCallback() {
        $this->mock->shouldReceive('activity->create')->once()->andReturn('restored');
        
        $this->assertEquals("restored", $this->observer->restored($this->mock));
    }
    
    function testForceDeleteCallback() {
        $this->mock->makePartial()->shouldReceive('activity->delete')->once()->andReturn('deleted');
        
        $this->assertEquals("deleted", $this->observer->deleted($this->mock));
    }
}

class ModelStub {
    function trashed() {
        return true;
    }
}