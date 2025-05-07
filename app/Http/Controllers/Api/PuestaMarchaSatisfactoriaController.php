<?php

namespace App\Http\Controllers\Api;

use App\Enums\PuestaMarchaSatisfactoriaEnum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;


class PuestaMarchaSatisfactoriaController extends Controller
{
    /**
     * Obtener tipos de Puesta Marcha Satisfactoria.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPuestaMarchaSatisfactoria()
    {
        Log::info('getPuestaMarchaSatisfactoria     ==============');

        try {
            $puestaMarchaSatisfactorias = PuestaMarchaSatisfactoriaEnum::getOptions();

            $respuesta = [
              'estado' => 'success',
              'codigo' => 200,
              'mensaje' => 'Puesta en Marcha Satisfactorias: obtenidas!',
              'puesta_marcha_satisfactoria' => $puestaMarchaSatisfactorias
            ];

        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener puestas en marcha.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }
}
