<?php

namespace App\Models\Onsite;

use App\Models\Company;
use App\Models\EmpresaInstaladora\EmpresaInstaladoraOnsite;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasSorts;
use App\Models\User;
use DB;

class EmpresaOnsite extends Model
{
  use HasSorts;

  const PAGOFACIL = 2;
  const RAPIPAGO = 3;
  const TELECOM = 4;
  const POSNET = 5; 
  const QUILMES = 6;
  const NIKE = 7;
  const YPF = 8;

  public $campos_permitidos_para_ordenar = [
    'id',
    'nombre',
    'requiere_tipo_conexion_local',
    'generar_clave_reparacion',
    'created_at',
    'updated_at',
  ];

  protected $table = "empresas_onsite";

  protected $fillable = [
    'company_id',
    'clave',
    'nombre',
    'primer_nombre',
    'apellido',
    'razon_social',
    'cuit',
    'tipo_iva',
    'pais',
    'provincia_onsite_id',
    'localidad_onsite_id',
    'localidad_texto',
    'codigo_postal',
    'email',
    'celular',
    'telefono',
    'web',
    'coordenadas',
    'observaciones',
    'requiere_tipo_conexion_local',
    'generar_clave_reparacion',
    'plantilla_mail_asignacion_tecnico_id',
    'plantilla_mail_responsable_id',
    'tecnico_id',
    'responsable',
    'email_responsable',
    'requiere_tipo_conexion_local',
    'tipo_terminales',
    'created_at',
    'updated_at'
  ];

  // RELACIONES

  // BELONGS TO
  public function company()
  {
    return $this->belongsTo(Company::class, 'company_id');
  }

  public function user_repuestos()
  {
    return $this->belongsTo(User::class, 'tecnico_id');
  }

  // HAS MANY
  public function reparaciones_onsite()
  {
    return $this->hasMany('App\Models\Onsite\ReparacionOnsite', 'id_empresa_onsite');
  }

  public function sucursales_onsite()
  {
    return $this->hasMany('App\Models\Onsite\SucursalOnsite', 'empresa_onsite_id');
  }

  public function obra_onsite()
  {
    return $this->hasOne('App\Models\Onsite\ObraOnsite', 'clave', 'clave');
  }

  public function empresa_instaladora_onsite()
  {
    return $this->belongsToMany(
      EmpresaInstaladoraOnsite::class,
      'empresas_instaladoras_empresas_onsite',
      'empresa_onsite_id',
      'empresa_instaladora_id'
    );
  }

  public function empresa_instaladora_empresa_onsite()
  {
    return $this->hasMany(EmpresaInstaladoraEmpresaOnsite::class, 'empresa_onsite_id');
  }
}
