<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Services\MailService;

class CommentTicketNotification extends Notification
{
    use Queueable;

    public $array_data;
    protected $mailService;
    protected $lineaClaseLogs;
    protected $message;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($array_data = null,$message = null, MailService $mailService = null)
    {
        $this->array_data = $array_data;
        $this->mailService = $mailService;
        $this->message = $message;
        $this->lineaClaseLogs = ' - ' . get_class($this) . ' - LINE: ';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', 'https://laravel.com')
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
        ];
    }
}
