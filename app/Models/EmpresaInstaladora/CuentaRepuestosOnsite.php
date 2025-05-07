<?php

namespace App\Models\EmpresaInstaladora;
use App\Models\Admin\Company;
use App\Models\Admin\Perfil;
use App\Models\Admin\User;
use App\Models\EmpresaInstaladora\EmpresaInstaladoraOnsite;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaRepuestosOnsite extends Model
{
    use HasFactory;

    protected $table = 'cuentas_repuestos_onsite';

    protected $fillable  = [
      
        'id',
        'company_id',
        'empresa_instaladora_id',
        'nombre',
        'numero_cuenta',
        'descuento_repuestos',
        'activa',
        'observaciones'
    ];

    public function company()
{
    return $this->belongsTo(Company::class, 'company_id');
}

public function empresa_instaladora_onsite()
{
    return $this->belongsTo(EmpresaInstaladoraOnsite::class, 'empresa_instaladora_id');
    
}
}
