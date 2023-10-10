<?php

namespace App\Queues;

use App\Models\Database;
use Illuminate\Queue\Events\JobProcessed;

class JobProcessedListener {
    public function __construct(protected Database $database)
    {
        
    }

    public function handle(JobProcessed $event) {
        echo "Job process: ". JobProcessed::class;
        echo "\n";
        echo "Status: ";
        if ($event->job->hasFailed()) {
            echo "failure\n";
            echo $event->job->getRawBody();
        } else {
            echo 'success';
            echo "\n";
            $this->database->getDatabase()
                ->table('jobs')
                ->where('id', $event->job->getJobId())
                ->delete();
        }
        echo "\n";
    }
}
