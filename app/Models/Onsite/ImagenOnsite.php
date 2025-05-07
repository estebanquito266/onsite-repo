<?php

namespace App\Models\Onsite;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenOnsite extends Model
{
  use HasFactory;

  protected $table = "imagenes_onsite";

  // constantes de tipos de imagen
  const TIPO_TRABAJO = 18;
  const TIPO_COMPROBANTE = 7;
  const CORTE_CANERIA = 22;
  const ANOMALIAS = 24;
  const PRESURIZACION = 26;
  const COMPROBANTE_SERVICIO_FIRMADO = 28;
  const TRABAJO_REALIZADO = 30;

  protected $fillable = [
    'reparacion_onsite_id',
    'archivo',
    'tipo_imagen_onsite_id',
    'descripcion',
    'company_id',
  ];

  // RELACIONES
  public function reparacion_onsite(): BelongsTo
  {
    return $this->belongsTo(ReparacionOnsite::class, 'reparacion_onsite_id');
  }

  public function Company(): BelongsTo
  {
    return $this->belongsTo(Company::class, 'company_id');
  }

  public function tipoImagenOnsite()
  {
    return $this->belongsTo(TipoImagenOnsite::class, 'tipo_imagen_onsite_id');
  }
}
