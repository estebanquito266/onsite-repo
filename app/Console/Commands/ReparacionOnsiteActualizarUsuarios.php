<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Onsite\ReparacionOnsite;

class ReparacionOnsiteActualizarUsuarios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reparacionOnsite:actualizarUsuarios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //public function actualizarUsuarios() {
        $actualizados = 0;

        $reparacionesOnsite = ReparacionOnsite::where('usuario_id', 399)->get();

        foreach ($reparacionesOnsite as $reparacionOnsite) {
            $historialEstadoOnsite = $reparacionOnsite->historialEstadosOnsite->first();
            if ($historialEstadoOnsite) {
                $reparacionOnsite->usuario_id = $historialEstadoOnsite->id_usuario;
                $reparacionOnsite->save();
                $actualizados++;
            }
        }

        if ($actualizados == 0) {
            $mje = "No se encontraron registros para actualizar.";
        } elseif ($actualizados == 1) {
            $mje = "Se actualizó una reparación onsite.";
        } else {
            $mje = "Se actualizaron $actualizados reparaciones onsite.";
        }

        echo $mje;

        //return redirect('/reparacionOnsite')->with('message', $mje );
        //}
    }
}
