<?php

namespace App\Events;

use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Illuminate\Events\Dispatcher;

class AppEventListener {
    private Dispatcher $dispatcher;
    
    public function __construct()
    {
        $this->dispatcher = new Dispatcher();
    }

    public function getDispatcher(): EventsDispatcher
    {
        return $this->dispatcher;
    }
}