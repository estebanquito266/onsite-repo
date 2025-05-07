<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
      'type' => 'users',
      'id' => (string) $this->getRouteKey(),
      'attributes' => [
        'company' => ($this->companies?$this->companies->first()->toArray():null),
        'name' => $this->name,
        'email' => $this->email,
        'domicilio' => $this->domicilio,
        'cuit' => $this->cuit,
        'telefono' => $this->telefono,
        'company_id' => $this->company_id,
        'id_tipo_visibilidad' => $this->id_tipo_visibilidad,
        'foto_perfil' => $this->foto_perfil,
        'api_token' => $this->api_token,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        'deleted_at' => $this->deleted_at,
        'role' => 0,
      ],
      'links' => [
        'self' => route('api.v1.reparaciones_onsite.show', $this),
      ]
    ];
  }
}
