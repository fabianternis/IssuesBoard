<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('items', function ($table) {
    $table->uuid('id')->primary(); 
    $table->uuid('project_id')->index();
    $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
    $table->string('name', 255);
    $table->text('description')->nullable();
    
    $table->enum('type', ['issue', 'idea', 'todo', 'other'])->default('idea');
    $table->string('state')->default('new');
    
    $table->string('external_url', 255)->nullable(); // e.g. github issue

    // soon: maybe a reference-image (stored on hackclub-cdn)
    
    $table->timestamps();
});