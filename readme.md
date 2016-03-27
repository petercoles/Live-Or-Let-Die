# Live or Let Die for Laravel

## Introduction

Coming soon ...

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

then add the middleware, "\PeterColes\LiveOrLetDie\Middleware\SessionTimeout::class", to the web (or whatever other group you're using for middleware) in your app/Http/Kernal.php file. For example:

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

If, and only if, your login route is called something other than 'login', then publish the config file and put your login route in there. Publishing is done by executing the following artisan command in your terminal.

```
php artisan vendor:publish --provider="PeterColes\LiveOrLetDie\Providers\LiveOrLetDieServiceProvider"
```

## Usage

To be continued ...
