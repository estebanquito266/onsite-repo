<?php

namespace App\Http\Resources\Onsite;

use Illuminate\Http\Resources\Json\JsonResource;

class SucursalOnsiteResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'type' => 'sucursales_onsite',
      'id' => (string) $this->getRouteKey(),
      'attributes' => [
        'company_id' => $this->company_id,
        'empresa_onsite_id' => $this->empresa_onsite_id,
        'localidad_onsite_id' => $this->localidad_onsite_id,
        'razon_social' => $this->razon_social,
        'direccion' => $this->direccion,
        'telefono_contacto' => $this->telefono_contacto,
        'nombre_contacto' => $this->nombre_contacto,
        'horarios_atencion' => $this->horarios_atencion,
        'observaciones' => $this->observaciones,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
      ],
      'links' => [
        // 'self' => route('api.v1.sucursales_onsite.show', $this),
      ]
    ];
  }
}
