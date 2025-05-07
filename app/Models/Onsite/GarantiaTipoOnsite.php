<?php

namespace App\Models\Onsite;

use App\Models\Admin\Company;
use App\Models\Config\TemplateComprobante;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarantiaTipoOnsite extends Model
{
    use HasFactory;
    protected $table = 'garantias_tipos_onsite';

    protected $fillable = [
        'id',
        'company_id',
        'nombre',
        'template_comprobante_id',
        'observaciones', 
            

    ];

    public function company()
{
    return $this->belongsTo(Company::class, 'company_id');
    
}

public function template_comprobante()
{
    return $this->belongsTo(TemplateComprobante::class, 'template_comprobante_id');
}
}
