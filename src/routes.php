<?php

Route::group(['middleware' => [
    \App\Http\Middleware\EncryptCookies::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\Session\Middleware\StartSession::class,
    \PeterColes\LiveOrLetDie\Middleware\SessionTimeout::class
]], function() {

    Route::get('session/ping', function() {
        return response(null, 200);
    });

    Route::get('session/remaining', function() {
        // nothing to do here since the response is handled by the middleware
    });

    Route::get('session/end', function() {
        // nothing to do here since the response is handled by the middleware
    });

});
