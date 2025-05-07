<?php

namespace App\Http\Resources\Onsite;

use Illuminate\Http\Resources\Json\JsonResource;

class EstadoOnsiteResource extends JsonResource
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
      'type' => 'estados_onsite',
      'id' => (string) $this->getRouteKey(),
      'attributes' => [
        'company_id' => $this->company_id,
        'nombre' => $this->nombre,
        'card_titulo' => $this->card_titulo,
        'card_intro' => $this->card_intro,
        'card_icono' => $this->card_icono,
        'tipo_estado_onsite_id' => $this->tipo_estado_onsite_id,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        'activo' => $this->activo,
        'cerrado' => $this->cerrado,
      ],
      'links' => [
        // 'self' => route('api.v1.estados_onsite.show', $this),
      ]
    ];
  }
}
