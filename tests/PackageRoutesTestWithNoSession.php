<?php

use Mockery as m;

class PackageRoutesWithNoSession extends BaseTest
{
    public function testRemainingRoute()
    {
        $this->init('session/remaining', null, true, true, true);

        $this->assertEquals([0, 200], $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testPingRoute()
    {
        $this->init('session/ping', null, true, true, true);

        $expected = ['trying to keep alive a session that has already expired', 400];
        $this->assertEquals($expected, $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testEndRoute()
    {
        $this->init('session/end', null, true, true, true);

        $this->assertEquals(['session ended', 200], $this->sessionTimeout->handle($this->request, $this->next));
    }
}
