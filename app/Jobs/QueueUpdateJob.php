<?php

namespace App\Jobs;
use App\Updates;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\SynchronizeUserDataJob;

class QueueUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        for ($i=1; $i <= 12 ; $i++) {
            $updates = Updates::with('user:id,email')->take('1000')->get(['id','changes','user_id'])->toArray();
            if($updates){
                SynchronizeUserDataJob::dispatch($updates)->onQueue('updateSync');
                $last_id = end($updates)['id'];
                Updates::where('id','<=', $last_id)->delete();
            }
        }
    }
}
