<?php

namespace Dev\Jobs;

use Dev\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateUserJob implements ShouldQueue
{
    public function handle(): void
    {
        $user = new User(['name' => 'Job name']);
        $user->save();
    }
}