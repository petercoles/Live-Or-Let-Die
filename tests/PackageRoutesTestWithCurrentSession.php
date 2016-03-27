<?php

use Mockery as m;

class PackageRoutesWithCurrentSession extends BaseTest
{
    public function setup()
    {
        $this->next = function() { return 'closure'; };
    }

    public function tearDown()
    {
        m::close();
    }

    public function testRemainingRoute()
    {
        $inRangeTime = time() - 20 * 60;
        $this->init('session/remaining', $inRangeTime, false);

        $this->assertEquals([600, 200], $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testPingRoute()
    {
        $inRangeTime = time() - 20 * 60;
        $this->init('session/ping', $inRangeTime, false);

        $this->assertEquals('closure', $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testEndRoute()
    {
        $inRangeTime = time() - 20 * 60;
        $this->init('session/end', $inRangeTime, false, true);

        $this->assertEquals(['session ended', 200], $this->sessionTimeout->handle($this->request, $this->next));
    }
}
