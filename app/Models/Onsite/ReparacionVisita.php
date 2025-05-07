<?php

namespace App\Models\Onsite;

use App\Models\Admin\Company;
use App\Models\Company as ModelsCompany;
use App\Models\Onsite\CompradorOnsite;
use App\Models\EmpresaInstaladora\EmpresaInstaladoraOnsite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReparacionVisita extends Model
{
    use HasFactory;

    protected $table = 'reparaciones_visitas';

    protected $fillable = [
        'id',
        'company_id',
        'reparacion_id',
        'orden',
        'fecha',
        'fecha_vencimiento',
        'fecha_nuevo_vencimiento',
        'motivo',
        'created_at',
        'updated_at',

    ];

    protected $casts = [
        'fecha' => 'datetime:Y-m-d H:i',
        'fecha_vencimiento' => 'datetime:Y-m-d H:i',
        'fecha_nuevo_vencimiento' => 'datetime:Y-m-d H:i'
    ];

    public function company()
    {
        return $this->belongsTo(ModelsCompany::class, 'company_id');
    }
}
