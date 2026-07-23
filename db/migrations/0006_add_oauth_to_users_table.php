<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

Capsule::schema()->table('users', function (Blueprint $table) {
    $table->string('github_id')->nullable()->unique();
    $table->string('github_email')->nullable();
    $table->text('github_token')->nullable();
    $table->text('github_refresh_token')->nullable();

    $table->string('hackclub_id')->nullable()->unique();
    $table->string('hackclub_email')->nullable();
    $table->text('hackclub_token')->nullable();
    $table->text('hackclub_refresh_token')->nullable();
});