<?php

namespace App\Models\Onsite;

use App\Models\Provincia;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use DB;

class LocalidadOnsite extends Model
{

  protected $table = "localidades_onsite";

  protected $fillable = [
    'id_provincia',
    'localidad',
    'localidad_estandard',
    'codigo',
    'km',
    'id_nivel',
    'atiende_desde',
    'id_usuario_tecnico',
    'company_id',
  ];


  // RELACIONES
  public function provincia()
  {
    return $this->belongsTo(Provincia::class, 'id_provincia');
  }

  public function terminales_onsite()
  {
    return $this->hasMany(TerminalOnsite::class, 'id_localidad');
  }

  public function sucursales_onsite()
  {
    return $this->hasMany(SucursalOnsite::class, 'localidad_onsite_id');
  }

  public function nivelOnsite()
  {
    return $this->belongsTo(NivelOnsite::class, 'id_nivel');
  }

  public function usuarioTecnico()
  {
    return $this->belongsTo(User::class, 'id_usuario_tecnico');
  }
}
