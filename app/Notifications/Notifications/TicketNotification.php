<?php

namespace App\Notifications;
use App\Channels\MailableChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Services\MailService;
use App\Mail\TicketMail;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendReminderEmail;
use App\Services\ParamCompaniesService;
use App\Models\Admin\Company;
use Illuminate\Support\Facades\Session;

class TicketNotification extends Notification
{
    use Queueable;

    public $array_data;
    protected $mailService;
    protected $lineaClaseLogs;
    protected $message;
    protected $paramCompaniesService;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($array_data = null,$message = null, MailService $mailService, ParamCompaniesService $paramCompaniesService)
    {
        $this->array_data = $array_data;
        $this->mailService = $mailService;
        $this->message = $message;
        $this->lineaClaseLogs = ' - ' . get_class($this) . ' - LINE: ';
        $this->paramCompaniesService = $paramCompaniesService;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        //return ['database','mail'];
        return ['database', MailableChannel::class];
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
        $company_id = Session::get('userCompanyIdDefault');
        $paramCompany = $this->paramCompaniesService->getParamCompany($company_id);
        $this->array_data['plantilla_mail_id']  = $paramCompany->plantilla_mail_ticket;
        $mailable = new TicketMail($this->mailService,$this->array_data);
        dispatch(new SendReminderEmail($this->array_data, $mailable));
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
