<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PerfilUsuario extends Model
{
	protected $table = "perfiles_usuarios";

	protected $fillable = [
		'id_usuario', 'id_perfil', 'id_tipo_ingreso',
		'company_id',
	];

	public static function obtenerPerfilDelUsuario($idUsuario)
	{
		return PerfilUsuario::join('perfiles', 'perfiles.id', '=', 'perfiles_usuarios.id_perfil')
			->select('perfiles_usuarios.id_usuario', 'perfiles_usuarios.id_perfil', 'perfiles_usuarios.id_tipo_ingreso', 'perfiles.nombre as nombreperfil')

			->where('perfiles_usuarios.id_usuario', '=', $idUsuario)
			->first();
	}

	public function user(){
		return $this->belongsTo('App\Models\User','id_usuario');
	}

	public function perfil(){
		return $this->belongsTo(Perfil::class,'id_perfil');	

}
}
