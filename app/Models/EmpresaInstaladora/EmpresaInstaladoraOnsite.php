<?php

namespace App\Models\EmpresaInstaladora;

use App\Models\Admin\Company;
use App\Models\Admin\User;
use App\Models\Provincia;
use App\Models\EmpresaInstaladora\EmpresaInstaladoraUser;
use App\Models\Onsite\LocalidadOnsite;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaInstaladoraOnsite extends Model
{
    use HasFactory;
    protected $table = 'empresas_instaladoras_onsite';

    protected $fillable = [
        'id',
        'id_unificado',
        'company_id',
        'nombre',
        'primer_nombre',
        'apellido',
        'razon_social',
        'cuit',
        'tipo_iva',
        'domicilio',
        'pais',
        'provincia_onsite_id',
        'localidad_onsite_id',
        'codigo_postal',
        'email',
        'celular',
        'telefono',
        'web',
        'coordenadas',
        'observaciones'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }



    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_onsite_id');
    }

    public function LocalidadOnsite()
    {
        return $this->belongsTo(LocalidadOnsite::class, 'localidad_onsite_id');
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'empresas_instaladoras_users', 'empresa_instaladora_id', 'user_id');
    }

    public function empresa_onsite()
    {
        return $this->belongsToMany(
            EmpresaOnsite::class,
            'empresas_instaladoras_empresas_onsite',
            'empresa_instaladora_id',
            'empresa_onsite_id'
        );
    }

    public function empresa_instaladora_empresa_onsite()
    {
        return $this->hasMany(EmpresaInstaladoraEmpresaOnsite::class, 'empresa_instaladora_id');
    }
}
