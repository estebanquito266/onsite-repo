<?php

namespace App\Models;

use App\Models\EmpresaInstaladora\EmpresaInstaladoraOnsite;
use App\Models\Onsite\ReparacionOnsite;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Laravel\Passport\HasApiTokens;
use App\Notifications\ResetPassword;

class User extends Authenticatable
{
	use HasApiTokens, \Illuminate\Notifications\Notifiable;

	const _TECHNICAL = 14;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password',
		'domicilio', 'cuit', 'telefono',
		'latitud',
		'longitud',
		'id_sucursal', 'id_tipo_visibilidad',
		'foto_perfil',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/*
	public function setPasswordAttribute($valor)
	{
		if (!empty($valor)) {
			$this->attributes['password'] = Hash::make($valor);
		}
	}
	*/

	public function setFotoPerfilAttribute($imagen)
	{
		if (!empty($imagen)) {
			$mimeType = $imagen->getClientMimeType();
			if ($mimeType == "image/jpeg" || $mimeType == "image/png") {
				$prefijo = Carbon::now()->hour . Carbon::now()->minute . Carbon::now()->second;
				//$fotoPerfil = $this->attributes['foto_perfil'];

				$nameOriginal = str_replace(" ", "", $imagen->getClientOriginalName());
				$name = 'avatar_' . $prefijo . '_' . $nameOriginal;
				$this->attributes['foto_perfil'] = $name;

				Storage::disk('avatars')->put($name, File::get($imagen));
			}
		}
	}

	public static function listar()
	{
		return DB::table('users')
			->join('tipos_visibilidades', 'tipos_visibilidades.id', '=', 'users.id_tipo_visibilidad')
			->select('users.*', 'tipos_visibilidades.nombre as nombretipovisibilidad')
			->orderBy('users.name', 'asc')
			->get();
	}

	public static function listarUsuario($idUsuario)
	{
		return DB::table('users')

			->select('users.*')
			->where('users.id', '=', $idUsuario)
			->get();
	}

	public static function listarUsuarios()
	{
		return DB::table('users')
			->join('perfiles_usuarios', 'perfiles_usuarios.id_usuario', '=', 'users.id')
			->selectRaw(" users.id as id, CONCAT(users.name, ' - ', users.email) as nombre ")
			->whereRaw(" perfiles_usuarios.id_perfil = 7 ")
			->orderBy('users.name', 'asc')
			->pluck('nombre', 'id');
	}

	public static function listarUsers()
	{
		return self::select('users.*')
			->orderBy('name', 'asc')
			->get();
		//->pluck('name', 'id');
	}



	public static function listado()
	{
		return DB::table('users')
			->select('users.*')
			->orderBy('name', 'asc')
			->pluck('name', 'id');
	}

	//RELACIONES

	//BELONGS TO
	/*
  public function company()
  {
      return $this->belongsTo(Company::class, 'company_id');
  }
  */

	//HAS MANY
	public function AauthAcessToken()
	{
		return $this->hasMany('\App\Models\OauthAccessToken');
	}

	public function ordenes_retiro_domicilio()
	{
		return $this->hasMany('App\Models\Retiros\OrdenRetiroDomicilio', 'vendedor_id');
	}

	public function turnos()
	{
		return $this->hasMany('App\Models\SucursalCalendario', 'vendedor_id');
	}

	public function ordenes_retiro_tecnocompro()
	{
		return $this->hasMany('App\Models\Retiros\OrdenRetiroTecnocompro', 'usuario_id');
	}

	public function historial_estados_onsite()
	{
		return $this->hasMany('App\Models\Onsite\HistorialEstadoOnsite', 'id_usuario');
	}

	public function historial_onsite_visibles()
	{
		return $this->belongsToMany('App\Models\Onsite\HistorialEstadoOnsite', 'historial_estados_onsite_visible_por_user', 'users_id', 'historial_estados_onsite_id');
	}

	//BELONGS TO MANY
	public function sucursales()
	{
		return $this->belongsToMany('App\Models\Sucursal');
	}

	public function companies()
	{
		return $this->belongsToMany('App\Models\Company', 'user_companies', 'user_id', 'company_id');
	}

	/*
  public function company_default()
  {
    return $this->belongsToMany('App\Models\Company', 'user_companies', 'user_id', 'company_id')->first();
  }  
  */

	public function company_default()
	{
		return $this->hasMany('App\Models\UserCompany', 'user_id')->first();
	}

	public function empresas_onsite()
	{
		return $this->belongsToMany('App\Models\Onsite\EmpresaOnsite', 'user_empresas_onsite', 'user_id', 'empresa_onsite_id');
	}

	public function reparaciones_onsite_confirmadas()
	{
		return $this->belongsToMany('App\Models\Onsite\ReparacionOnsite', 'reparaciones_onsite_confirmadas_por_users', 'user_id', 'reparacion_onsite_id');
	}
	public function perfiles()
	{
		return $this->belongsToMany('App\Models\Perfil', 'perfiles_usuarios', 'id_usuario', 'id_perfil');
	}

	public function perfil_default()
	{
		return $this->belongsToMany('App\Models\Perfil', 'perfiles_usuarios', 'id_usuario', 'id_perfil')->first();
	}

	public function perfil_usuario()
	{
		return $this->hasMany(PerfilUsuario::class, 'id_usuario')
			//->with('user')
			->with('perfil');
	}

	public function empresa_instaladora()
	{
		return $this->belongsToMany(EmpresaInstaladoraOnsite::class, 'empresas_instaladoras_users', 'user_id', 'empresa_instaladora_id');
	}

	public function reparacion_onsite()
	{
		return $this->hasMany(ReparacionOnsite::class, 'id_tecnico_asignado');
	}



	/**
	 * Send the password reset notification.
	 *
	 * @param string $token
	 * @return void
	 */
	public function sendPasswordResetNotification($token)
	{
		$this->notify(new ResetPassword($token));
	}
}
