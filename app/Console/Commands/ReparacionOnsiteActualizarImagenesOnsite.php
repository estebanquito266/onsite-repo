<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Onsite\ImagenOnsite;
use App\Models\Onsite\ReparacionOnsite;
use App\Models\Onsite\TipoImagenOnsite;

class ReparacionOnsiteActualizarImagenesOnsite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reparacionOnsite:actualizarImagenesOnsite';

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
        //public function actualizarImagenesOnsite() {
        $reparacionesOnsite = ReparacionOnsite::all();
        $actualizados = 0;

        foreach ($reparacionesOnsite as $reparacionOnsite) {
            if ($reparacionOnsite->imagenesOnsite->count() == 0) {
                if (!empty($reparacionOnsite->foto_comprobante)) {
                    $this->crearImagenOnsite($reparacionOnsite->id, $reparacionOnsite->foto_comprobante, TipoImagenOnsite::TIPO_COMPOBANTE);
                }
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

    private function crearImagenOnsite($reparacionOnsiteId, $archivo, $tipoImagenOnsiteId)
    {
        $imagenOnsite = new ImagenOnsite();
        $imagenOnsite->reparacion_onsite_id = $reparacionOnsiteId;
        $imagenOnsite->archivo = $archivo;
        $imagenOnsite->tipo_imagen_onsite_id = $tipoImagenOnsiteId;
        $imagenOnsite->save();
    }
}
