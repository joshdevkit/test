<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionStatusNotification extends Notification
{
    use Queueable;

    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->from('lovelynathaleebautista@gmail.com', 'SITE Inventory System')
        ->subject('Transaction Status Update')
        ->line($this->message)
        ->action('View Request', url('/request'))
        ->line('Thank you for using our application!');
    }
}
