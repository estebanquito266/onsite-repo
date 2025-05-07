<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Onsite\UserService;
use Illuminate\Http\Request;
use Log;
use Mapper;
use Redirect;
use Session;

class UserController extends Controller
{

	protected $userService;

	public function __construct(UserService $userService)
	{
		$this->userService = $userService;
	}

	public function googleAutoAddress()
	{
		return view('welcome');
	}


	public function configUsuario($id)
	{
		$usuario = User::find($id);

		return view('usuario.configUsuario', [
			'usuario' => $usuario,

		]);
	}

	public function updateConfigUsuario(Request $request, $id)
	{

		$usuario = User::find($id);
		$usuario->fill($request->all());
		$usuario->save();

		Session::flash('message', 'Datos Modificados correctamente');

		return Redirect::to('/admin');
	}

	public function getVisitasPorTecnico()
	{
		$tecnicos = $this->userService->getVisitasPorTecnico();

		return response()->json($tecnicos);
	}

	public function mapeo_usuarios()
	{

		$usuarios = User::all();


		$i = 1;
		foreach ($usuarios as $usuario) {

			if ($usuario->longitud != null) {
				if ($i == 1) {
					//Mapper::map($usuario->latitud, $usuario->longitud);
					Mapper::map(-34.605864, -58.454632);
					$i++;
				}

				$info_usuario = $usuario->name . '<br>' . $usuario->domicilio . '<br>' .
					(count($usuario->empresa_instaladora) > 0 ? $usuario->empresa_instaladora[0]->nombre : '');
				Mapper::informationWindow($usuario->latitud, $usuario->longitud, $info_usuario, ['open' => false, 'maxWidth' => 300, 'autoClose' => true, 'markers' => ['title' => $usuario->name]]);
			}
		}





		return view('usuario.maps');
	}
}
