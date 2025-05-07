<?php

namespace App\Models\Respuestos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiezaRespuestosOnsite extends Model
{
    use HasFactory;


    protected $table = "piezas_respuestos_onsite";

    protected $fillable = [
        'id',
        'company_id',
        
        'numero',
        'spare_parts_code',
        'part_name',
        'precio_fob',
        'description',
        'part_image',
        'dimensiones',
        'peso',
        'pia'

    ];

  
    public function company()
    {
      return $this->belongsTo('App\Models\Admin\Company');
    }
  
    public function modelo_pieza()
      {
          return $this->hasMany(ModeloPiezaOnsite::class, 'pieza_respuestos_id')
          ->with('modelo_respuestos_onsite')
          ->with('pieza_respuestos_onsite');
      }

      public function precio()
      {
        return $this->hasMany(PrecioPiezaRepuestoOnsite::class, 'piezas_respuestos_onsite_id')->orderBy('version', 'desc');
      }

    }
