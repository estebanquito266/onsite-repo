<?php
namespace App\Services\Onsite;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Onsite\NotaOnsite;


class NotaOnsiteService
{
    public function __construct()
    {
        
    }

    public function create($request){
    	$request['user_id'] =  Auth::user()->id;
		  $notaOnsite = NotaOnsite::create($request->all());

          return $notaOnsite;
    }
}