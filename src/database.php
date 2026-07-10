<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
// require_once __DIR__ . '/helpers.php';

use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => config('database.driver'),
    'host'      => config('database.host_FULL'),
    'database'  => config('database.name'),
    'username'  => config('database.username'),
    'password'  => config('database.password'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);


$capsule->bootEloquent();