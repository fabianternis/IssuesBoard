<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('media', function ($table) {
    $table->uuid('id')->primary(); 
    // $table->uuidMorphs('owner')->index();
    $table->uuidMorphs('parent')->index();
    //$table->string('custom_url')->nullable(); /* change: upload from url to cdn (found this in api-docs at: https://cdn.hackclub.com/docs/api) */
    // $table->string('r2_path')->nullable();
    $table->uuid('cdn_id')->nullable(); /* may be removed ... */
    $table->string('cdn_filename')->nullable();
    $table->string('cdn_url')->nullable(); /* could be "built" from othe rfata but ill leave it for now */
    $table->text('description')->nullable();
    
    $table->timestamps();
});