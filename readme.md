# Live or Let Die for Laravel

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/petercoles/Live-Or-Let-Die/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/petercoles/Live-Or-Let-Die/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/petercoles/Live-Or-Let-Die/badges/build.png?b=master)](https://scrutinizer-ci.com/g/petercoles/Live-Or-Let-Die/build-status/master)
[![License](http://img.shields.io/:license-mit-blue.svg)](http://doge.mit-license.org)

## Introduction

As we build sites with more-and-more features executed and managed directly in the user's browser, the connection between browser and server becomes increasingly strained. While our users are beavering away in their browser, we risk our server-side Laravel application quietly terminating the associated sessions, causing ajax-powered actions to fail unexpectedly and in ways that are inexplicable and difficult to understand for our users.

One way to address this is to lengthen our sessions. Even ignoring the security implications of this, it only mitigates
and doesn't solve the problems.

This package extends Laravel by injecting three routes that can be used by frontend applications to work with remote Laravel sessions.

## Installation

At the command line run

```
composer require petercoles/live-or-let-die
```

then add the service provider to the providers entry in your config/app.php file

```
    'providers' => [
        // ...
        PeterColes\LiveOrLetDie\Providers\LiveOrLetDieServiceProvider::class,
        // ...
    ],
```

then add the middleware, "\PeterColes\LiveOrLetDie\Middleware\SessionTimeout::class", to the web (or whatever other middleware group you're using for web requests) in your app/Http/Kernal.php file. For example:

```
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \PeterColes\LiveOrLetDie\Middleware\SessionTimeout::class,
        ],

        ...
    ];
```
This will keep your session alive for normal web requests. If you use a different group for your ajax requests, include it in there. But don't include it for stateless requests, e.g. API calls.

## Configuration

If, and only if, your login or logout routes are called something other than 'login' and 'logout', then publish the config file and put your routes in there. Publishing is done by executing the following artisan command in your terminal.

```
php artisan vendor:publish --provider="PeterColes\LiveOrLetDie\Providers\LiveOrLetDieServiceProvider"
```

## Usage

This package makes available three routes designed for use by ajax calls to enable frontend applications to work with remote Laravel sessions:
+ `session/remaining` returns the number of seconds remaininig in the current session, without affecting time remaining
+ `session/ping` can be used to extend the session length for events that might not otherwise be notified to the remote server, e.g. clicking on javascript managed tabs
+ `session/ends` allows the frontend to request termination of the remote session.

These routes are injected automatically - there's no need for you to add them to your routes file.

## Issues

This package was developed to meet a specific need and then generalised for wider use. If you have a use case not currently met, or see something that appears to not be working correctly, please raise an issue at the [github repo](https://github.com/petercoles/Live-Or-Let-Die/issues)

## Security Vulnerabilities

If you discover a security vulnerability within this package, please send an e-mail to me at peterdcoles@gmail.com. I take security very seriously and any security vulnerabilities will be addressed promptly.

## License

This package is licensed under the [MIT license](http://opensource.org/licenses/MIT).
