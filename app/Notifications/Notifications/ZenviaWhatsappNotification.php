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
use App\Services\MailService;
use App\Services\ResourceService;

class ZenviaWhatsappNotification extends Notification
{
    use Queueable;

    public $array_data;
    protected $mailService;
    protected $resourceService;
    protected $lineaClaseLogs;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($array_data = null, ResourceService $resourceService)
    {
        $this->array_data = $array_data;
        $this->resourceService = $resourceService;
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
        return [ResourceChannel::class];
    }


    /* Clase Personalizada para enviar notification por resource */
    public function toResource($notifiable)
    {          
         
         $sendNotificationResource = $this->resourceService->sendZenviaWhatsapp($this->array_data);

         return $sendNotificationResource;        
    }    


}
