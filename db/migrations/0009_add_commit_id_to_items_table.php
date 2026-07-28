<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

Capsule::schema()->table('items', function (Blueprint $table) {
    $table->string('commit_id')->nullable();
});