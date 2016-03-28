<?php

namespace PeterColes\LiveOrLetDie\Middleware;

use Closure;
use Illuminate\Config\Repository as Config;
use Illuminate\Auth\AuthManager as Auth;
use Illuminate\Session\Store as Session;

class SessionTimeout
{
    protected $session;

    protected $timeout;

    protected $login;

    protected $auth;

    public function __construct(Session $session, Config $config, Auth $auth)
    {
        $this->session = $session;

        $this->timeout = $config->get('session.lifetime') * 60;

        $this->login = $config->get('liveorletdie.login', 'login');

        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // don't interfer with normal logout requests
        if ($request->is('logout')) {
            $this->session->forget('last_activity');
            return $next($request);
        }

        if ($this->endSession($request)) {
            return $this->terminateAndRespond($request, $next);
        }

        if ($request->is('session/remaining')) {
            return response($this->timeout - (time() - $this->session->get('last_activity')));
        }

        $this->session->put('last_activity', time());

        return $next($request);
    }

    protected function endSession($request)
    {
        return !$request->is($this->login) && ($this->timedOut() || $request->is('session/end'));
    }

    protected function timedOut()
    {
        return !$this->session->has('last_activity') || (time() - $this->session->get('last_activity')) > $this->timeout;
    }

    protected function terminateAndRespond($request, $next)
    {
        $this->auth->logout();
        $this->session->forget('last_activity');

        if ($request->is('session/end')) {
            return response('session ended', 200);
        }

        if ($request->is('session/remaining')) {
            return response(0, 200);
        }

        if ($request->is('session/ping')) {
            return response('trying to keep alive a session that has already expired', 400);
        }

        return $next($request);
    }
}
