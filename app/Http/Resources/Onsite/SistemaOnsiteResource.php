<?php

namespace App\Http\Resources\Onsite;

use Illuminate\Http\Resources\Json\JsonResource;

class SistemaOnsiteResource extends JsonResource
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
        'type' => 'sistemas_onsite',
        'id' => (string) $this->getRouteKey(),
        'attributes' => [
          'company_id' => $this->company_id,
          'empresa_onsite_id' => $this->empresa_onsite_id,
          'sucursal_onsite_id' => $this->sucursal_onsite_id,
          'nombre' => $this->nombre,
          'comentarios' => $this->comentarios,
          'created_at' => $this->created_at,
          'updated_at' => $this->updated_at,
        ],
        'links' => [
          // 'self' => route('api.v1.sistemas_onsite.show', $this),
        ]
      ];
    }
}
