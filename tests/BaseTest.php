<?php

use Mockery as m;

abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
    protected function init($route, $lastActivity, $guest = false, $logout = false)
    {
        $this->request($route);
        $this->session($lastActivity);
        $this->config();
        $this->auth($guest, $logout);

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

    protected function session($lastActivity)
    {
        $this->session = m::mock('\\Illuminate\\Session\\Store');

        if ($lastActivity) {
            $this->session->shouldReceive('has')->with('last_activity')->andReturn(true);
            $this->session->shouldReceive('get')->with('last_activity')->andReturn($lastActivity);
        } else {
            $this->session->shouldReceive('has')->with('last_activity')->andReturn(false);
        }

        $this->session->shouldReceive('put');
    }

    protected function auth($guest, $logout)
    {
        $this->auth = m::mock('\\Illuminate\\Auth\\AuthManager');

        $this->auth->shouldReceive('guest')->andReturn($guest);

        if ($logout) {
            $this->auth->shouldReceive('logout')->once();
        }
    }

    protected function config()
    {
        $this->config = m::mock('\\Illuminate\\Config\\Repository');
        $this->config->shouldReceive('get')->with('session.lifetime')->andReturn(30);
        $this->config->shouldReceive('get')->with('liveorletdie.login', 'login')->andReturn('login');
    }
}
