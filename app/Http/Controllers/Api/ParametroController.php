<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Onsite\ParametroService;
use Illuminate\Http\Request;

class ParametroController extends Controller
{
    protected $parametroService;
	
	public function __construct(ParametroService $parametroService){

		$this->parametroService = $parametroService;
	}	

    public function getParametroTerminosCondiciones(Request $request)
    {
        $parametro = 'ONSITE_TERMINOS_CONDICIONES';
        $valor = 'valor_texto';
        $parametro = $this->parametroService->getParametroTerminosCondiciones($parametro,$valor);

        if($parametro)
        {
            $respuesta = [
                'estado' => 'exitosa',
                'codigo' => 200,
                'valor'  => $parametro
            ];
        }else{
            $respuesta = [
                'estado' => 'erronea',
                'codigo' => 500,
                'mensaje'  => 'No existe un parametro con ese nombre'
            ];
        }

        return response()->json($respuesta,$respuesta['codigo']);
    }

 
}
