<?php

namespace App\Http\Resources\Onsite;

use Illuminate\Http\Resources\Json\JsonResource;

class EmpresaOnsiteResource extends JsonResource
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
          'type' => 'empresas_onsite',
          'id' => (string) $this->getRouteKey(),
          'attributes' => [
            'company_id' => $this->company_id,
            'nombre' => $this->nombre,
            'requiere_tipo_conexion_local' => $this->requiere_tipo_conexion_local,
            'generar_clave_reparacion' => $this->generar_clave_reparacion,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
          ],
          'links' => [
            'self' => route('api.v1.empresas_onsite.show', $this),
          ]
        ];
    }
}
