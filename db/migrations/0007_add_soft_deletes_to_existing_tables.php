<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

Capsule::schema()->table('users', function (Blueprint $table) {
    $table->timestamp('deleted_at')->nullable();
});
Capsule::schema()->table('projects', function (Blueprint $table) {
    $table->timestamp('deleted_at')->nullable();
});
Capsule::schema()->table('items', function (Blueprint $table) {
    $table->timestamp('deleted_at')->nullable();
});