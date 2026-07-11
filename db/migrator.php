<?php

/* HOW WORKS
- if not exists creates "migrations"-table
- get existing migrations
- lists all files in /migrations/
- checkes migrations-table for existing ones
- executes the ones that do not exist yet and add them to db
*/


// require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/src/helpers.php';
require_once dirname(__DIR__) . '/src/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;

if (!Capsule::schema()->hasTable('migrations')) {
    Capsule::schema()->create('migrations', function ($table) {
       $table->string('name')->unique();
       $table->timestamp('migrated_at')->useCurrent(); 
    });
}

$executedMigrations = Capsule::table('migrations')->pluck('name')->toArray();

$migrations = glob(__DIR__ . '/migrations/*.php');

foreach($migrations as $migration) {
    $name = basename($migration);
    if(!in_array($name, $executedMigrations)) {
        require_once $migration;

        Capsule::table('migrations')->insert(['name' => $name]);
        // echo('migrated '. $name . '\n'); /* \n did also not functopn – didn't know that singe-quotes do that much
        echo("migrated {$name}\n");
    }
}