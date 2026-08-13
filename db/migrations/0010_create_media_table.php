<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('media', function ($table) {
    $table->uuid('id')->primary(); 
    // $table->uuidMorphs('owner')->index();
    $table->uuidMorphs('parent');
    //$table->string('custom_url')->nullable(); /* change: upload from url to cdn (found this in api-docs at: https://cdn.hackclub.com/docs/api) */
    // $table->string('r2_path')->nullable();
    $table->uuid('remote_id')->nullable(); /* may be removed ... */
    $table->string('filename')->nullable();
    $table->string('url')->nullable(); /* could be "built" from othe rfata but ill leave it for now */
    $table->text('description')->nullable();
    
    $table->timestamps();
});