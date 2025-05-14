<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OverdueNotification extends Notification
{
    use Queueable;


    protected $request;
    /**
     * Create a new notification instance.
     */
    public function __construct($request)
    {
        $this->request = $request;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'request_id' => $this->request->id,
            'item_name' => $this->request->supply_item,
            'message' => 'The item "' . $this->request->supply_item . '" you borrowed has not been returned and is overdue by 3 days.',
            'updated_at' => $this->request->updated_at,
            'due_date' => now()->subDays(3)->toDateString(),
            'user_id' => $this->request->requested_by
        ];
    }
}
