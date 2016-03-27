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

## Configuration

If, and only if, your login route is called something other than 'login', then publish the config file and put your login route in there:

```
php artisan vendor:publish --provider="PeterColes\LiveOrLetDie\Providers\LiveOrLetDieServiceProvider"
```

## Usage

To be continued ...
