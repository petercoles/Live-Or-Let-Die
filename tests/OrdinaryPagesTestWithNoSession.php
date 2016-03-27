<?php

require_once(__DIR__.'/mocks/functions.php');

use Mockery as m;

class OrdinaryPagesWithNoSession extends BaseTest
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
        $this->init('login', null, false);

        $this->assertEquals('closure', $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testLogoutRoute()
    {
        $this->init('logout', null, false, true);

        $this->assertEquals('login', $this->sessionTimeout->handle($this->request, $this->next));
    }

    public function testOtherPageRoute()
    {
        $this->init('foo', null, false, true);

        $this->assertEquals('login', $this->sessionTimeout->handle($this->request, $this->next));
    }
}
