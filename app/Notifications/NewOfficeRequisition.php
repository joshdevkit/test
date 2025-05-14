<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOfficeRequisition extends Notification
{
    use Queueable;
    protected $officeRequisitionId;
    protected $activity;
    protected $status;
    /**
     * Create a new notification instance.
     */
    public function __construct($officeRequisitionId, $activity, $status)
    {
        $this->officeRequisitionId = $officeRequisitionId;
        $this->activity = $activity;
        $this->status = $status;
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line($this->activity)
            ->action('View Requisition', url('/requisitions/' . $this->officeRequisitionId))
            ->line('Status: ' . $this->status)->line('Thank you for your prompt response.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return ['requisition_id' => $this->officeRequisitionId, 'activity' => $this->activity, 'status' => $this->status,];
    }
}
