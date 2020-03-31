<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SynchronizeUserDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The records of updates made by a user
     *
     * @var array
     */
    protected $updates;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($updates)
    {
        $this->updates = $updates;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $id = 0;
        foreach ($this->updates as $key => $value) {
            $id = $value['id'];
            $message = "[$id] ";
            foreach ($value['changes'] as $key => $value) {
                $message .= " $key: $value,";
            }
            Log::channel('logUserUpdate')->info($message);
        };
    }
}
