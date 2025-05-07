<?php

namespace App\Http\Resources\Onsite;

use Illuminate\Http\Resources\Json\JsonResource;

class UnidadesInterioresOnsiteResource extends JsonResource
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
      'type' => 'unidades_interiores_onsite',
      'id' => (string) $this->getRouteKey(),
      'attributes' => [
        'company_id' => $this->company_id,
        'empresa_onsite_id' => $this->empresa_onsite_id,
        'sucursal_onsite_id' => $this->sucursal_onsite_id,
        'sistema_onsite_id' => $this->sistema_onsite_id,
        'clave' => $this->clave,
        'modelo' => $this->modelo,
        'direccion' => $this->direccion,
        'serie' => $this->serie,
        'observaciones' => $this->observaciones,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
      ],
      'links' => [
        // 'self' => route('api.v1.sistemas_onsite.show', $this),
      ]
    ];
    }
}
