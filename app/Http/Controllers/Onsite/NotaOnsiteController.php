<?php

namespace App\Http\Controllers\Onsite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Onsite\NotaOnsiteService;

class NotaOnsiteController extends Controller
{
  protected $notaOnsiteService;

  public function __construct(
    NotaOnsiteService $notaOnsiteService)
  {
      $this->notaOnsiteService = $notaOnsiteService;
    
  } 

  public function agregar(Request $request)
  {

    $notaOnsite = $this->notaOnsiteService->create($request);

    if ($notaOnsite) {
      return response()->json([
        'mensaje' => 'Nota Creada correctamente. Id: '. $notaOnsite->id
      ]);
    }

    
  }
}
