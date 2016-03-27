<?php

use Mockery as m;

class OrdinaryPagesWithCurrentSession extends BaseTest
{
    public function setup()
    {
        $this->next = function() { return 'closure'; };
    }

    public function tearDown()
    {
        m::close();
    }

    public function testLoginRoute()
    {
        $inRangeTime = time() - 20 * 60;
        $this->init('login', $inRangeTime, false);

        $this->assertEquals('closure', $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testOtherPageRoute()
    {
        $inRangeTime = time() - 20 * 60;
        $this->init('foo', $inRangeTime, false);

        $this->assertEquals('closure', $this->sessionTimeout->handle($this->request, $this->next));
    }
}
