<?php

use Mockery as m;

class PackageRoutesWithExpiredSession extends BaseTest
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
        $outOfRangeTime = time() - 40 * 60;
        $this->init('session/remaining', $outOfRangeTime, false, true);

        $this->assertEquals([0, 200], $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testPingRoute()
    {
        $outOfRangeTime = time() - 40 * 60;
        $this->init('session/ping', $outOfRangeTime, false, true);

        $expected = ['trying to keep alive a session that has already expired', 400];
        $this->assertEquals($expected, $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testEndRoute()
    {
        $outOfRangeTime = time() - 40 * 60;
        $this->init('session/end', $outOfRangeTime, false, true);

        $this->assertEquals(['session ended', 200], $this->sessionTimeout->handle($this->request, $this->next));
    }
}
