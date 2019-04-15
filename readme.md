# Live or Let Die for Laravel

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b8661cda-dfcc-4ca1-807d-73f8e78f11fd/mini.png)](https://insight.sensiolabs.com/projects/b8661cda-dfcc-4ca1-807d-73f8e78f11fd)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/petercoles/Live-Or-Let-Die/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/petercoles/Live-Or-Let-Die/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/petercoles/Live-Or-Let-Die/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/petercoles/Live-Or-Let-Die/?branch=master)
[![Build Status](https://travis-ci.org/petercoles/Live-Or-Let-Die.svg?branch=master)](https://travis-ci.org/petercoles/Live-Or-Let-Die)
[![License](http://img.shields.io/:license-mit-blue.svg)](http://doge.mit-license.org)

## Introduction

As we build sites with more-and-more features executed and managed directly in the user's browser, the connection between browser and server becomes increasingly strained. While our users are beavering away in their browsers, we risk our server-side Laravel application quietly terminating the associated sessions, logging them out and causing ajax-powered actions to fail unexpectedly and in ways that are inexplicable and difficult to understand for our users.

One way to address this is to lengthen our sessions. But even ignoring the security implications of this, it merely mitigates the problem; it doesn't solve it.

This package extends Laravel by injecting three routes that can be used by frontend applications to work with remote Laravel sessions. They can be used to see for how much longer an authentication session will last, and to extend or end it in response to browser-based actions that might otherwise go unnoticed by our server.

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

the middleware, "\PeterColes\LiveOrLetDie\Middleware\SessionTimeout::class", will be added automatically to the web routes group and will be applied to all routes that it contains. If you use a different group for your ajax requests, then it may be added to that group in your app/Http/Kernal.php file, or wherever you have defined it. For example:

```
    protected $middlewareGroups = [
        'myMiddleware' => [
            ...
            \PeterColes\LiveOrLetDie\Middleware\SessionTimeout::class,
        ],
    ];
```
However, this middleware should not be included for stateless requests, e.g. API calls.

## Configuration

If, and only if, your auth routes are called something other than 'login', 'register' and 'logout', then publish the config file and put your routes in there. Publishing is done by executing the following artisan command in your terminal.

```
php artisan vendor:publish --provider="PeterColes\LiveOrLetDie\Providers\LiveOrLetDieServiceProvider"
```

## Usage

This package makes available three routes designed for use by ajax calls to enable frontend applications to work with remote Laravel sessions:
+ `session/remaining` returns the number of seconds remaining in the current session, without affecting time remaining
+ `session/ping` can be used to extend the session length for events that might not otherwise be notified to the remote server, e.g. clicking on javascript managed tabs
+ `session/end` allows the frontend to request termination of the remote session.

These routes are injected automatically - there's no need for you to add them to your routes file.

## Issues

This package was developed to meet a specific need and then generalised for wider use. If you have a use case not currently met, or see something that appears to not be working correctly, please raise an issue at the [github repo](https://github.com/petercoles/Live-Or-Let-Die/issues)

## Security Vulnerabilities

If you discover a security vulnerability within this package, please send an e-mail to me at peterdcoles@gmail.com. I take security very seriously and any security vulnerabilities will be addressed promptly.

## License

This package is licensed under the [MIT license](http://opensource.org/licenses/MIT).
