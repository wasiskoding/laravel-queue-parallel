<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class UpdateUserChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Collection $users;
    /**
     * Create a new job instance.
     */
    public function __construct(protected array $userIds)
    {
        $this->users = User::whereIn('id', $userIds)->get();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->users->each(function ($user) {
            // send email
            sleep(1);
            $user->update([
                'email_verified_at' => now(),
            ]);
        });
    }
}
