<?php

namespace PeterColes\LiveOrLetDie\Middleware;

use Closure;
use Illuminate\Config\Repository as Config;
use Illuminate\Auth\AuthManager as Auth;
use Illuminate\Http\Request;
use Illuminate\Session\Store as Session;

class SessionTimeout
{
    protected $session;

    protected $timeout;

    protected $login;

    protected $logout;

    protected $auth;

    public function __construct(Session $session, Config $config, Auth $auth)
    {
        $this->session = $session;

        $this->timeout = $config->get('session.lifetime') * 60;

        $this->login = $config->get('liveorletdie.login', 'login');
        $this->logout = $config->get('liveorletdie.logout', 'logout');

        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // don't interfere with normal logout requests
        if ($request->is($this->logout)) {
            $this->session->forget('last_activity');
            return $next($request);
        }

        // check if we should the authenticated session to end
        // and if yes, terminate it
        if ($this->endSession($request)) {
            return $this->terminateAndRespond($request, $next);
        }

        // if we just want the time remaining claculate and return that
        // this will stop any further processing, including preventing unintended extension of the underlying session
        if ($request->is('session/remaining')) {
            return response($this->timeout - (time() - $this->session->get('last_activity')));
        }

        // for all other requests, including pings to extend the session, we update our timer and continue normally
        $this->session->put('last_activity', time());

        $response = $next($request);

        $this->afterRequest($request);

        return $response;
    }

    /**
     * Determine whether the user is authenticating, i.e. logging in or signing up
     *
     * @param  \Illuminate\Http\Request $request
     * @return boolean 
     */
    protected function authenticating(Request $request)
    {
        return $request->is($this->login) || $request->is('register');
    }

    /**
     * Determine whether the session should be ended due to timeout or frontend request
     * We whitelist the login page from this assessment
     *
     * @param  \Illuminate\Http\Request $request
     * @return boolean 
     */
    protected function endSession(Request $request)
    {
        return !$this->authenticating($request) && ($this->timedOut() || $request->is('session/end'));
    }

    /**
     * Determine whether timeout has occurred or been forced by other activity on the site
     * If there's no data to do the timeout check, then we assume the session has been otherwise ended
     *
     * @return boolean 
     */
    protected function timedOut()
    {
        return !$this->session->has('last_activity') || (time() - $this->session->get('last_activity')) > $this->timeout || $this->auth->guest();
    }

    /**
     * Logout and clear our the last_activity timer - one or both of which actions may be redundant, but not harmful
     * Then, for our package routes, compose a suitable response, and for other routes continue processing as normal
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    protected function terminateAndRespond(Request $request, Closure $next)
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

    /**
     * After the request has been processed, check if it was for actually
     * authenticating and if not, i.e. redirect to login page and unset the
     * last_activity timer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function afterRequest(Request $request)
    {
        if ($this->authenticating($request) && $this->auth->guest()) {
            $this->session->forget('last_activity');
        }
    }
}
