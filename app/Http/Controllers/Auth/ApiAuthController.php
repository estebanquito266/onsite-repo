<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\UserCompany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ApiAuthController extends Controller
{
  /**
   * Undocumented function
   *
   * @param Request $request
   * @return void
   */
  public function login (Request $request) {

    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email|max:255',
        'password' => 'required|string|min:3',
    ]);

    if ($validator->fails())
    {
      $errores = implode(' - ', $validator->errors()->all());
        
      abort(422, $errores);
    }

    $user = User::where('email', $request->email)->first();

    if ($user) {      

        if( Auth::attempt(['email' => $request['email'], 'password' => $request['password'] ]) ) {

            $token = $user->createToken(env('APP_NAME', 'API'))->accessToken;

            $user_resource = UserResource::make($user);

            $response = [
              'token' => $token,
              'user' => $user_resource,
            ];
            $request->headers->set('Access-Control-Allow-Origin', '*');
            $request->headers->set('Accept', 'application/json');            

            return response($response, 200);

        } else {
            abort(401, 'Usuario o contraseña incorrectos');
        }
    } else {
      abort(401, 'Usuario o contraseña incorrectos');
    }
  }

  /**
   * Undocumented function
   *
   * @param Request $request
   * @return void
   */
  public function logout (Request $request) {
    if (Auth::check()) {
       Auth::user()->AauthAcessToken()->delete();
       abort(200, 'se cerro la sesion satisfactoriamente');
    }

    abort(401, 'Usuario o contraseña incorrectos');
  }

  /**
   * Undocumented function
   *
   * @param Request $request
   * @return void
   */
  public function loginBgh(Request $request)
  {
    $request['email'] = env('BGH_USER');
    $request['password'] = env('BGH_PASSWORD');

    Log::info('API - loginBgh: ');
    Log::info($request['email']);

    Session::put('userCompanyIdDefault', env('BGH_COMPANY_ID', 2));

    return $this->login($request);
  }
}
