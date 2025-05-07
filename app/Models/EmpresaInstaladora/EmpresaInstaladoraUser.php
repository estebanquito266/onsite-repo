<?php

namespace App\Models\EmpresaInstaladora;

use App\Models\Admin\Company;
use App\Models\Admin\Perfil;
use App\Models\Admin\User;
use App\Models\EmpresaInstaladora\EmpresaInstaladoraOnsite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaInstaladoraUser extends Model
{
    use HasFactory;

    protected $table = 'empresas_instaladoras_users';

    protected $fillable = [
        'id',
        'company_id',
        'empresa_instaladora_id',
        'user_id',
        'perfil_id', //(generar perfiles responsable y tecnico)
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

public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function perfil()
{
    return $this->belongsTo(Perfil::class, 'perfil_id');
}

}
