<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->table('users', function ($table) {
    $table->json('settings')->nullable();
});