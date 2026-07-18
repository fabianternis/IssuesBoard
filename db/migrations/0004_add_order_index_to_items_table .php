<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->table('items', function ($table) {
    $table->integer('order_index')->nullable(); 
});