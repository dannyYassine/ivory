<?php

namespace Dev\Models; 

use Illuminate\Database\Capsule\Manager as Capsule;
 
class Database {
    public function __construct() 
    {
        $capsule = new Capsule;
        $capsule->addConnection([
             'driver' => $_ENV['DATABASE_DRIVER'] ?? 'pgsql',
             'host' => $_ENV['DATABASE_HOST'] ?? 'postgres',
             'database' => $_ENV['DATABASE_DATABASE'] ?? 'postgres',
             'username' => $_ENV['DATABASE_USERNAME'] ?? 'postgres',
             'password' => $_ENV['DATABASE_PASSWORD'] ?? 'postgres',
             'charset' => 'utf8',
             'collation' => 'utf8_unicode_ci',
             'prefix' => '',
        ]);
        $capsule->bootEloquent();
    }
}