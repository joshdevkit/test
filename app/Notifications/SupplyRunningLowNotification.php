<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupplyRunningLowNotification extends Notification
{
    use Queueable;

    protected $supply;

    public function __construct($supply)
    {
        $this->supply = $supply;
    }

    public function via(object $notifiable): array
    {
        return ['database'];  // We are sending it to the database for now
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "The supply item '{$this->supply->item}' is running low with only {$this->supply->items->count()} items left."
        ];
    }
}
