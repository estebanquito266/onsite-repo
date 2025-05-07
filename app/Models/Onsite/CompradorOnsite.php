<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompradorOnsite extends Model
{
    use HasFactory;


    protected $table = 'compradores_onsite';
    protected $fillable = [

        'company_id',
        'dni',
        'nombre',
        'primer_nombre',
        'apellido',
        'pais',
        'provincia_onsite_id',
        'localidad_onsite_id',
        'domicilio',
        'codigo_postal',
        'email',
        'telefono',
        'celular',
        'observaciones',

    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    

    public function Provincia()
	{
		return $this->belongsTo('App\Models\Config\Provincia', 'provincia_onsite_id');
	}

    public function LocalidadOnsite()
    {
        return $this->belongsTo(LocalidadOnsite::class, 'localidad_onsite_id');
    }
}
