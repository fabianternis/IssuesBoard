<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('project_users', function ($table) {
    $table->uuid('project_id');
    $table->uuid('user_id');
    
    $table->text('description')->nullable(); 
    $table->timestamps();

    $table->primary(['project_id', 'user_id']);

    $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});