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

    public function testRemainingRouteWhileLoggedIn()
    {
        $inRangeTime = time() - 20 * 60;
        $this->init('session/remaining', $inRangeTime, false, false, false);

        $this->assertEquals([600, 200], $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testRemainingRouteWhileLoggedOut()
    {
        $inRangeTime = time() - 20 * 60;
        $this->init('session/remaining', $inRangeTime, true, true, true);

        $this->assertEquals([0, 200], $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testPingRouteWhileLoggedIn()
    {
        $inRangeTime = time() - 20 * 60;
        $this->init('session/ping', $inRangeTime, false, false, false);

        $this->assertEquals('closure', $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testPingRouteWhileLoggedOut()
    {
        $inRangeTime = time() - 20 * 60;
        $this->init('session/ping', $inRangeTime, true, true, true);

        $expected = ['trying to keep alive a session that has already expired', 400];
        $this->assertEquals($expected, $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testEndRouteWhileLoggedIn()
    {
        $inRangeTime = time() - 20 * 60;
        $this->init('session/end', $inRangeTime, true, true, false);

        $this->assertEquals(['session ended', 200], $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testEndRouteWhileLoggedOut()
    {
        $inRangeTime = time() - 20 * 60;
        $this->init('session/end', $inRangeTime, true, true, true);

        $this->assertEquals(['session ended', 200], $this->sessionTimeout->handle($this->request, $this->next));
    }

}
