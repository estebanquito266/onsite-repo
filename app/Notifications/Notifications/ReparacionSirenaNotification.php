<?php

namespace App\Notifications;

use App\Channels\MailableChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Channels\ResourceChannel;
use App\Jobs\SendReminderEmail;
use App\Mail\ReparacionSirenaMail;
use App\Mail\SendMail;
use App\Services\ClientesService;
use App\Services\MailService;
use App\Services\ResourceService;

class ReparacionSirenaNotification extends Notification
{
    use Queueable;

    public $array_data;
    protected $mailService;
    protected $resourceService;
    protected $lineaClaseLogs;
    protected $clientesService;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        $array_data = null, 
        MailService $mailService, 
        ResourceService $resourceService,
        ClientesService $clientesService
        )
    {
        $this->array_data = $array_data;
        $this->mailService = $mailService;
        $this->resourceService = $resourceService;
        $this->clientesService = $clientesService;

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
        //return [MailableChannel::class];
        return [ResourceChannel::class];
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
        // $data_mail = $this->mailService->enviarMailReparacionSirenaSend($this->array_data);
        $mailable = new ReparacionSirenaMail($this->mailService, $this->array_data);
        Log::info('Reparacion Sirena  Notification: ARRAY: ' . $this->lineaClaseLogs . __LINE__);
        Log::info($this->array_data);

        dispatch(new SendReminderEmail($this->array_data, $mailable));
    }

    /* Clase Personalizada para enviar notification por resource */
    public function toResource($notifiable)
    {
        Log::alert('notificacion sirena reparación...');
        $data = $this->mailService->enviarMailReparacionSirenaSend($this->array_data);
        $data['source'] = env('ZENVIA_REPARACION_LEAD_SOURCE');

        $sendNotificationResource = $this->resourceService->createLead($data);

        $result = $this->clientesService->setProspectByReparacion($this->array_data['reparacion_id'], $sendNotificationResource);

        return $sendNotificationResource;
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
