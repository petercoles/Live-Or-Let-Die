<?php

use Mockery as m;

abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->next = function() { return 'closure'; };
    }

    public function tearDown()
    {
        m::close();
    }

    protected function init($route, $lastActivity, $forget, $logout, $guest)
    {
        $this->request($route);
        $this->session($lastActivity, $forget);
        $this->config();
        $this->auth($logout, $guest);

        $this->sessionTimeout = new PeterColes\LiveOrLetDie\Middleware\SessionTimeout(
            $this->session, $this->config, $this->auth
        );
    }

    protected function request($route)
    {
        $this->request = m::mock('\\Illuminate\\Http\\Request');

        $candidates = [
            'login',
            'logout',
            'foo',
            'session/remaining',
            'session/ping',
            'session/end',
        ];

        foreach ($candidates as $candidate) {
            $this->request->shouldReceive('is')->with($candidate)->andReturn($candidate == $route);
        }
    }

    protected function session($lastActivity, $forget)
    {
        $this->session = m::mock('\\Illuminate\\Session\\Store');

        if ($lastActivity) {
            $this->session->shouldReceive('has')->with('last_activity')->andReturn(true);
            $this->session->shouldReceive('get')->with('last_activity')->andReturn($lastActivity);
        } else {
            $this->session->shouldReceive('has')->with('last_activity')->andReturn(false);
        }

        if ($forget) {
            $this->session->shouldReceive('forget')->with('last_activity')->once();
        }

        $this->session->shouldReceive('put');
    }

    protected function auth($logout, $guest)
    {
        $this->auth = m::mock('\\Illuminate\\Auth\\AuthManager');

        if ($logout) {
            $this->auth->shouldReceive('logout')->once();
        }

        $this->auth->shouldReceive('guest')->andReturn($guest);
    }

    protected function config()
    {
        $this->config = m::mock('\\Illuminate\\Config\\Repository');
        $this->config->shouldReceive('get')->with('session.lifetime')->andReturn(30);
        $this->config->shouldReceive('get')->with('liveorletdie.login', 'login')->andReturn('login');
        $this->config->shouldReceive('get')->with('liveorletdie.logout', 'logout')->andReturn('logout');
    }
}
