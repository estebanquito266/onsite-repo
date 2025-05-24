<?php

namespace App\Notifications;

use App\Channels\MailableChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendReminderEmail;
use App\Mail\OcaOrdenRetiroMail;
use App\Mail\SendMail;
use App\Services\MailService;

class OcaOrdenRetiroNotification extends Notification
{
    use Queueable;

    public $array_data;
    protected $mailService;
    protected $lineaClaseLogs;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($array_data = null,MailService $mailService)
    {
        $this->array_data = $array_data;
        $this->mailService = $mailService;
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
        //return ['mail'];
        return [MailableChannel::class];
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

    /* Clase Personalizada para enviar mail en 5.3 utilizando mailable */
    public function toMailable($notifiable)
    {  
        $mailable = new OcaOrdenRetiroMail($this->mailService,$this->array_data);
        Log::info('Orden Oca Retiro  Notification: ARRAY: ' . $this->lineaClaseLogs . __LINE__  );
        Log::info($this->array_data);
        
        dispatch(new SendReminderEmail($this->array_data,$mailable));
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
            //
        ];
    }
}
