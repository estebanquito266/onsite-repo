<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
      $response = $next($request);

      $response->headers->set('Access-Control-Allow-Origin' , '*');
      $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
      $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');
      
      return $response;


      /* return $next($request)
        ->headers->set('Access-Control-Allow-Origin', '*')
        ->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->headers->set('Access-Control-Allow-Headers',' Origin, Content-Type, Accept, Authorization, X-Request-With')
        ->headers->set('Access-Control-Allow-Credentials',' true'); */
    }
}
