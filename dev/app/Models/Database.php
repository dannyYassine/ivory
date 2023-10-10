<?php

namespace App\Models; 

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

class Database {
    public Capsule $capsule;

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
        $capsule->setAsGlobal();
        $capsule->setEventDispatcher(new Dispatcher());
        $capsule->bootEloquent();
        $capsule->getContainer()->singleton('db', function () use ($capsule) {
            return $capsule;
        });

        $this->capsule = $capsule;
    }

    public function getDatabase(): Capsule {
        return $this->capsule;
    }
}