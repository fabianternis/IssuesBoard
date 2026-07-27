<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

Capsule::schema()->table('projects', function (Blueprint $table) {
    $table->json('settings')->nullable();
});