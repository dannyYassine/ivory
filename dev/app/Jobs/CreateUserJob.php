<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
 
class CreateUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
 
    public function handle(): void
    {
        $user = new User(['name' => 'Job name', 'email' => 'danny'.rand().'@ivory.com', 'password' => '1234']);
        $user->save();
    }

    public function fire(): void
    {
        $user = new User(['name' => 'Job name', 'email' => 'danny'.rand().'@ivory.com', 'password' => '1234']);
        $user->save();
    }
}