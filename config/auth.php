<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard'     => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */

    'guards' => [
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    | We use a custom provider configuration so that Auth::attempt() correctly
    | queries the 'username' column instead of Laravel's default 'email'.
    |
    | The EloquentUserProvider's retrieveByCredentials() uses the non-password
    | keys from the credentials array as WHERE clauses. Since we pass
    | ['username' => ..., 'password' => ...], it will query WHERE username = ?
    | This works automatically — no extra config needed.
    |
    | We also set 'username' here as an explicit reminder and for any packages
    | that read this config value.
    */

    'providers' => [
        'users' => [
            'driver'   => 'eloquent',
            'model'    => App\Models\User::class,
            // Tell Laravel which field is the "username" for login.
            // Auth::attempt() uses the credential array keys, not this value,
            // but this documents the intent and is used by some packages.
            'username' => 'username',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => 10800,

];
