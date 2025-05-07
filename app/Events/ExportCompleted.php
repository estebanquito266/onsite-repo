<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExportCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;


    public function __construct($message)
    {
        $this->message = $message;

        /* El evento a notificar se maneja desde: app\Listeners\NotifyExportCompletion.php */
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    /* public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    } */
}
