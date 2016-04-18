<?php

use Mockery as m;

class OrdinaryPagesWithCurrentSession extends BaseTest
{
    public function testLoginRoute()
    {
        $inRangeTime = time() - 20 * 60;
        $this->init('login', $inRangeTime, true, false, true);

        $this->assertEquals('closure', $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testLogoutRoute()
    {
        $inRangeTime = time() - 20 * 60;
        $this->init('logout', $inRangeTime, true, false, false);

        $this->assertEquals('closure', $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testOtherLoggedInPageRoute()
    {
        $inRangeTime = time() - 20 * 60;
        $this->init('foo', $inRangeTime, false, false, false);

        $this->assertEquals('closure', $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testOtherLoggedOutPageRoute()
    {
        $inRangeTime = time() - 20 * 60;
        $this->init('bar', $inRangeTime, true, true, true);

        $this->assertEquals('closure', $this->sessionTimeout->handle($this->request, $this->next));
    }
}
