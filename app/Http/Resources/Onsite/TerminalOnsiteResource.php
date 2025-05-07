<?php

namespace App\Http\Resources\Onsite;

use Illuminate\Http\Resources\Json\JsonResource;

class TerminalOnsiteResource extends JsonResource
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
      'type' => 'terminales_onsite',
      'id' => (string) $this->getRouteKey(),
      'attributes' => [
        'nro' => $this->nro,
        'company_id' => $this->company_id,
        'sucursal_onsite_id' => $this->sucursal_onsite_id,
        'all_terminales_sucursal' => $this->all_terminales_sucursal,
        'marca' => $this->marca,
        'modelo' => $this->modelo,
        'serie' => $this->serie,
        'rotulo' => $this->rotulo,
        'observaciones' => $this->observaciones,
        'empresa_onsite_id' => $this->empresa_onsite_id,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
      ],
      'links' => [
        // 'self' => route('api.v1.terminales_onsite.show', $this),
      ]
    ];
  }
}
