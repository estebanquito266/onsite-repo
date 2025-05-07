<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObraChecklistOnsite extends Model
{
    use HasFactory;

    protected $table = 'obras_checklist_onsite';

    protected $fillable = [
        'company_id',
        'obra_onsite_id',
        'requiere_zapatos_seguridad',
        'requiere_casco_seguridad',
        'requiere_proteccion_visual',
        'requiere_proteccion_auditiva',
        'requiere_art',
        'cuit',
        'razon_social',
        'clausula_no_arrepentimiento',
        'cnr_detalle'
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company','company_id');
    }

    public function obra_onsite()
    {
        return $this->belongsTo('App\Models\Onsite\ObraOnsite','obra_onsite_id');
    }
}
