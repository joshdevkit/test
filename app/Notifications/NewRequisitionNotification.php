<?php

namespace App\Notifications;

use App\Models\Requisition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewRequisitionNotification extends Notification
{
    use Queueable;

    public $requisition;

    /**
     * Create a new notification instance.
     */
    public function __construct(Requisition $requisition)
    {
        $this->requisition = $requisition;
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
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'requisition_id' => $this->requisition->id,
            'activity' => $this->requisition->activity,
            'subject' => $this->requisition->subject,
            'course_year' => $this->requisition->course_year,
            'date_time_filed' => $this->requisition->date_time_filed,
            'instructor_name' => auth()->user()->name,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'requisition_id' => $this->requisition->id,
            'message' => 'A new requisition has been made by Laboratory, please check it out.',
        ]);
    }
}
