<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RolPerfil extends Model
{
	protected $table = "roles_perfiles";

	protected $fillable = ['id_perfil', 'id_rol', 'company_id'];

	public static function permisosUsuario($idUser){
		return DB::table('roles_perfiles')
			->join('perfiles_usuarios', 'perfiles_usuarios.id_perfil', '=', 'roles_perfiles.id_perfil')	
			->join('roles', 'roles.id', '=', 'roles_perfiles.id_rol')					
			->select( 'roles.ruta')
			->where('perfiles_usuarios.id_usuario', '=', $idUser)
			->get();		
	}	
}
