<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasSorts;

use DB;

class SistemaOnsite extends Model
{

    use HasSorts;

    protected $table = "sistemas_onsite";

    protected $fillable = [
        'id',
        'company_id',
        'empresa_onsite_id',
        'sucursal_onsite_id',
        'obra_onsite_id',
        'obra_onsite_id_unificado',
        'comprador_onsite_id',
        'fecha_compra',
        'numero_factura',
        'nombre',
        'comentarios',
        'created_at',
        'updated_at',
        'company_id',
    ];
    public function company_onsite()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function empresa_onsite()
    {
        return $this->belongsTo('App\Models\Onsite\EmpresaOnsite', 'empresa_onsite_id');
    }

    public function unidades_exteriores()
    {
        return $this->hasMany('App\Models\Onsite\UnidadExteriorOnsite', 'sistema_onsite_id');
    }

    public function unidades_interiores()
    {
        return $this->hasMany('App\Models\Onsite\UnidadInteriorOnsite', 'sistema_onsite_id');
    }

    public function sucursal_onsite()
    {
        return $this->belongsTo('App\Models\Onsite\SucursalOnsite', 'sucursal_onsite_id');
    }

    public function obra_onsite()
    {
        return $this->belongsTo(ObraOnsite::class, 'obra_onsite_id');
    }

    public function obra_onsite_unificado()
    {
        return $this->belongsTo(ObraOnsite::class, 'obra_onsite_id_unificado');
    }
    public function comprador_onsite()
    {
        return $this->belongsTo(CompradorOnsite::class, 'comprador_onsite_id');
    }

    /* public function reparacion_onsite()
    {
        return $this->hasMany(ReparacionOnsite::class, 'sistema_onsite_id')->orderBy('id', 'desc')->first();
    } */

    public function reparacion_onsite()
    {
        return $this->hasMany(ReparacionOnsite::class, 'sistema_onsite_id')->with('tecnicoAsignado');
    }


    
   


    
}
