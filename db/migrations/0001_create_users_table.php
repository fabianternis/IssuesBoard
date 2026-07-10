<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('users', function ($table) {
    $table->uuid('id')->primary(); 
    $table->string('email', 255)->unique();
    $table->string('username', 50)->unique();
    $table->string('password', 255);
});