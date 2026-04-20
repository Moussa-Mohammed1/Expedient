<?php

namespace App\Jobs;

use App\Models\SuspendedUser;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ExpireSuspensionsJob
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        SuspendedUser::where('status', 'active')
        ->where('expires_at', '<=', Carbon::now())
        ->update(['status' => 'expired']);
    }
}
