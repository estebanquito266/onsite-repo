<?php

namespace App\Models\Onsite;

use App\Models\Admin\Company;
use App\Models\Company as ModelsCompany;
use App\Models\Onsite\CompradorOnsite;
use App\Models\EmpresaInstaladora\EmpresaInstaladoraOnsite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarantiaOnsite extends Model
{
    use HasFactory;
    
    protected $table = 'garantias_onsite';

    protected $fillable = [
        'id',
        'company_id',
        'nombre',
        'empresa_instaladora_id',
        'user_id',
        'obra_onsite_id',
        'sistema_onsite_id',
        
        
        'observaciones',
        'fecha',
        'garantia_tipo_onsite_id',
        'informe_observaciones',
        'destinatario_informe'
    ];

public function company()
{
    return $this->belongsTo(ModelsCompany::class, 'company_id');
}

public function empresa_instaladora()
{
    return $this->belongsTo(EmpresaInstaladoraOnsite::class, 'empresa_instaladora_id');
}

public function obra_onsite()
{
    return $this->belongsTo(ObraOnsite::class, 'obra_onsite_id');
}

public function sistema_onsite()
{
    return $this->belongsTo(SistemaOnsite::class, 'sistema_onsite_id')
    ->with('unidades_exteriores')
    ->with('unidades_exteriores');
}



public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function tipo()
{
    return $this->belongsTo(GarantiaTipoOnsite::class, 'garantia_tipo_onsite_id');
}

}
