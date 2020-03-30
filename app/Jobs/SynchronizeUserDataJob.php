<?php

namespace App\Jobs;
use App\Updates;

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
        $id = 0;
        $updates = Updates::with('user:id,email')->take('1000')->get(['id','changes','user_id'])->toArray();
        if($updates){
            foreach ($updates as $key => $value) {
                $id = $value['id'];
                $message = "[$id] ";
                foreach ($value['changes'] as $key => $value) {
                    $message .= " $key: $value,";
                }
                Log::channel('logUserUpdate')->info($message);
            };
            Updates::where('id','<=', $id)->delete();
        }
    }
}
