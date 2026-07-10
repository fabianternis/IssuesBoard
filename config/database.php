<?php

return [
    'type' => env('DB_DRIVER', 'mysql'), // LEGACY
    'driver' => env('DB_DRIVER', 'mysql'),
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', '3306'),
    'name' => env('DB_NAME', 'database'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORF', 'root'),


    
    /* not configureable (yet) */
    'prefix' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',


    /* Qulaity of Life (if it is called like that) */
    'host_FULL' => config('databse.host', 'localhost') . ':' . config('database.port', '3306'),
];