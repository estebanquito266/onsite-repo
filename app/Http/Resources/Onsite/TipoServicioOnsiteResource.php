<?php

namespace App\Http\Resources\Onsite;

use Illuminate\Http\Resources\Json\JsonResource;

class TipoServicioOnsiteResource extends JsonResource
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
          'type' => 'tipos_servicios_onsite',
          'id' => (string) $this->getRouteKey(),
          'attributes' => [
            'company_id' => $this->company_id,
            'nombre' => $this->nombre,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
          ],
          'links' => [
            'self' => route('api.v1.tipos_servicios_onsite.show', $this),
          ]
        ];
    }
}
