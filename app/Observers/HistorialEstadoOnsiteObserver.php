<?php

namespace App\Observers;

use App\Models\Onsite\HistorialEstadoOnsite;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class HistorialEstadoOnsiteObserver
{
    /**
     * Handle the HistorialEstadoOnsite "created" event.
     *
     * @param  \App\Models\HistorialEstadoOnsite  $historialEstadoOnsite
     * @return void
     */
    public function created(HistorialEstadoOnsite $historialEstadoOnsite)
    {
      $users = User::whereHas('perfiles', function (Builder $query) {
        $query->where('perfiles.visualizar_historial_estado_onsite', 1);
      })->whereHas('companies', function(Builder $query) use ($historialEstadoOnsite) {
        $query->where('companies.id', $historialEstadoOnsite->company_id);
      });

      foreach ($users->get() as $user) {
        DB::table('historial_estados_onsite_visible_por_user')->insert([
          'users_id' => $user->id,
          'historial_estados_onsite_id' => $historialEstadoOnsite->id,
        ]);
      }
    }

    /**
     * Handle the HistorialEstadoOnsite "updated" event.
     *
     * @param  \App\Models\HistorialEstadoOnsite  $historialEstadoOnsite
     * @return void
     */
    public function updated(HistorialEstadoOnsite $historialEstadoOnsite)
    {
        //
    }

    /**
     * Handle the HistorialEstadoOnsite "deleted" event.
     *
     * @param  \App\Models\HistorialEstadoOnsite  $historialEstadoOnsite
     * @return void
     */
    public function deleted(HistorialEstadoOnsite $historialEstadoOnsite)
    {
        //
    }

    /**
     * Handle the HistorialEstadoOnsite "restored" event.
     *
     * @param  \App\Models\HistorialEstadoOnsite  $historialEstadoOnsite
     * @return void
     */
    public function restored(HistorialEstadoOnsite $historialEstadoOnsite)
    {
        //
    }

    /**
     * Handle the HistorialEstadoOnsite "force deleted" event.
     *
     * @param  \App\Models\HistorialEstadoOnsite  $historialEstadoOnsite
     * @return void
     */
    public function forceDeleted(HistorialEstadoOnsite $historialEstadoOnsite)
    {
        //
    }
}
