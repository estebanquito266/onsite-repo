<?php

namespace App\Models;

use App\Models\Onsite\TipoServicioOnsite;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    CONST COMPANY_ALL = 1;
    CONST DEFAULT = 1;
    CONST COMPANY_BGH = 2;
    CONST BGH = 2;
    
    protected $fillable = ['nombre', 'logo'];

    public static function listado(){
        return self::orderBy('id', 'asc')
          ->get();
        //->pluck("nombre", "id");
    }
    public function colores()
    {
        return $this->hasMany('App\Models\Color', 'company_id');
    }

    public function cuentas_vendedor_mercadopago()
    {
        return $this->hasMany('App\Models\CuentaVendedorMercadoPago');
    }

    public function elockers_etiquetas()
    {
        return $this->hasMany('App\Models\ElockerEtiqueta');
    }

    public function empresas_onsite()
    {
        return $this->hasMany('App\Models\EmpresaOnsite');
    }

    public function estados_derivacion()
    {
        return $this->hasMany('App\Models\EstadoDerivacion');
    }

    public function estados_onsite()
    {
        return $this->hasMany('App\Models\EstadoOnsite');
    }

    public function fallas()
    {
        return $this->hasMany('App\Models\Falla');
    }

    public function localidades()
    {
        return $this->hasMany('App\Models\Localidad');
    }

    public function marcas()
    {
        return $this->hasMany('App\Models\Marca');
    }

    public function marcas_oficiales()
    {
        return $this->hasMany('App\Models\MarcaOficial');
    }

    public function mensajes_web()
    {
        return $this->hasMany('App\Models\MensajeWeb');
    }

    public function metodos_cobro()
    {
        return $this->hasMany('App\Models\MetodoCobro');
    }

    public function modelos()
    {
        return $this->hasMany('App\Models\Modelo');
    }

    public function motivos_rechazo_presupuesto()
    {
        return $this->hasMany('App\Models\MotivoRechazoPresupuesto');
    }

    public function multiplicadores_fee()
    {
        return $this->hasMany('App\Models\MultiplicadorFee');
    }

    public function niveles_onsite()
    {
        return $this->hasMany('App\Models\NivelOnsite');
    }

    public function estados()
    {
        return $this->hasMany('App\Models\Estado');
    }

    public function perfiles()
    {
        return $this->hasMany('App\Models\Perfil');
    }

    public function roles()
    {
        return $this->hasMany('App\Models\Rol');
    }

    public function roles_perfiles()
    {
        return $this->hasMany('App\Models\RolPerfil');
    }

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    public function tipos_servicios_onsite(): HasMany
    {
        return $this->hasMany(TipoServicioOnsite::class, 'company_id');
    }

    public function obras_onsite(): HasMany
    {
        return $this->hasMany('App\Models\Onsite\ObraOnsite', 'company_id');
    }

    // BELONGS TO MANY
    public function usuarios()
    {
      return $this->belongsToMany('App\Models\User', 'user_companies', 'company_id', 'user_id');
    }
}
