<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class SendLowStockNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $asset;

    public function __construct($asset)
    {
        $this->asset = $asset;
    }

    public function handle()
    {
        $admin = User::where('is_admin', true)->first();

        $admin->notify(new LowStockNotification($this->asset));
    }
}


