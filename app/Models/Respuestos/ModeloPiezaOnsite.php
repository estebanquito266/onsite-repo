<?php

namespace App\Models\Respuestos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeloPiezaOnsite extends Model
{
    use HasFactory;

    protected $table = 'modelos_piezas_onsite';

    protected $fillable = [
        'id', 
        'company_id',
        'created_at',
        'updated_at',
        'deleted_at',

        'modelo_respuestos_id',
        'pieza_respuestos_id'
    ];

    public function modelo_respuestos_onsite()
  {
    return $this->belongsTo(ModeloRespuestosOnsite::class, 'modelo_respuestos_id');
  }

  public function pieza_respuestos_onsite()
  {
    return $this->belongsTo(PiezaRespuestosOnsite::class, 'pieza_respuestos_id')->with('precio')->orderBy('numero');
  }

  public function company()
	{
		return $this->belongsTo('App\Models\Admin\Company');
	}

    

}
