<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LowStockNotification extends Notification
{
    use Queueable;

    public $asset;

    public function __construct($asset)
    {
        $this->asset = $asset;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Restock Needed: {$this->asset->name}")
            ->line("The asset '{$this->asset->name}' is running low.")
            ->line("Only {$this->asset->count} items left in store.")
            ->line('Please restock soon.');
    }

    public function toArray($notifiable)
    {
        return [
            'asset' => $this->asset->name,
            'count' => $this->asset->count,
            'message' => "{$this->asset->name} is low in stock ({$this->asset->count})",
        ];
    }
}
