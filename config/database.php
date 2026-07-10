<?php

return [
    'type' => env('DB_DRIVER', 'mysql'), // LEGACY
    'driver' => env('DB_DRIVER', 'mysql'),
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', '3306'),
    'name' => env('DB_NAME', 'issuesboard'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', 'root'),


    
    /* not configureable (yet) */
    'prefix' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',


    /* Qulaity of Life (if it is called like that) */
    'host_FULL' => env('DB_HOST', 'localhost') . ':' . env('DB_PORT', '3306'),
];