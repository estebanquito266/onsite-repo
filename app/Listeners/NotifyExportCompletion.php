<?php

namespace App\Listeners;

use App\Events\ExportCompleted;
use App\Models\Notificacion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class NotifyExportCompletion
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ExportCompleted $event)
    {

        Notificacion::create([
            'notificacion' => $event->message,
            'tipo' => 'exportacion'
        ]);

        session()->flash('message_completed', $event->message);
    }
}
