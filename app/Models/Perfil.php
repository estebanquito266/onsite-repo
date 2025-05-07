<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Perfil extends Model
{
	const TECNOCOMPRO = 45;
	const PROVEEDOR = 30;
	const ADMIN = 1;
	const CLIENTE = 7;
  const TECNICO_ONSITE = 11;
  const ADMIN_ONSITE = 62;
  const ADMIN_ONSITE_BGH = 61;

	protected $table = "perfiles";

	protected $fillable = ['nombre', 'visualizar_historial_estado_onsite', 'company_id'];

  // RELACIONES
  public function usuarios()
  {
    return $this->belongsToMany('App\Models\User', 'perfiles_usuarios', 'id_perfil', 'id_usuario');
  }
}
