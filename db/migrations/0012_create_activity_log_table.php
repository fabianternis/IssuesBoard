<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('activity_log', function ($table) {
    // Table could have also been named "actions" or "performed_actions"

    $table->uuid('id')->primary(); 

    $table->foreignUuid('user_id');
    $table->uuidMorphs('object');

    $table->string('action')->default('update')->nullable();
    $table->json('data_pre')->nullable();
    $table->json('data_post')->nullable();

    // $table->date('performed_at');
    $table->datetime('performed_at');
});