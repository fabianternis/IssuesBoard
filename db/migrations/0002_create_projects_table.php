<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('projects', function ($table) {
    $table->uuid('id')->primary(); 
    $table->uuid('user_id')->index(); 
    $table->string('name', 64);
    $table->string('description', 255);
    $table->string('repo_url', 255)->unique();
});