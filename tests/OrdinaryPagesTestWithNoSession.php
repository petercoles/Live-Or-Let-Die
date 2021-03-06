<?php

require_once(__DIR__.'/mocks/functions.php');

use Mockery as m;

class OrdinaryPagesWithNoSession extends BaseTest
{
    /*
     * Normally an expired session will cause all normal
     * routes to redirect to the login route. The login
     * route itself is an exception to avoid looping
     * redirects and instead drops through to the closure
     */
    public function testLoginRoute()
    {
        $this->init('login', null, true, false, true);

        $this->assertEquals('closure', $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testLogoutRoute()
    {
        $this->init('logout', null, true, null, null);

        $this->assertEquals('closure', $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testOtherLoggedInPageRoute()
    {
        $this->init('foo', null, true, true, true);

        $this->assertEquals('closure', $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testOtherLoggedOutPageRoute()
    {
        $this->init('bar', null, true, true, true);

        $this->assertEquals('closure', $this->sessionTimeout->handle($this->request, $this->next));
    }
}
