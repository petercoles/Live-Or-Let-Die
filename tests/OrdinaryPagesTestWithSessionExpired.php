<?php

require_once(__DIR__.'/mocks/functions.php');

use Mockery as m;

class OrdinaryPagesWithSessionExpired extends BaseTest
{
    public function setup()
    {
        $this->next = function() { return 'closure'; };
    }

    public function tearDown()
    {
        m::close();
    }

    /*
     * Normally an expired session will cause all normal
     * routes to redirect to the login route. The login
     * route itself is an exception to avoid looping
     * redirects and instead drops through to the closure
     */
    public function testLoginRoute()
    {
        $outOfRangeTime = time() - 40 * 60;
        $this->init('login', $outOfRangeTime, true, false, true);

        $this->assertEquals('closure', $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testLogoutRoute()
    {
        $outOfRangeTime = time() - 40 * 60;
        $this->init('logout', $outOfRangeTime, true, false, false);

        $this->assertEquals('closure', $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testOtherLoggedInPageRoute()
    {
        $outOfRangeTime = time() - 40 * 60;
        $this->init('foo', $outOfRangeTime, true, true, false);

        $this->assertEquals('closure', $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testOtherLoggedOutPageRoute()
    {
        $outOfRangeTime = time() - 40 * 60;
        $this->init('foo', $outOfRangeTime, true, true, true);

        $this->assertEquals('closure', $this->sessionTimeout->handle($this->request, $this->next));
    }
}
