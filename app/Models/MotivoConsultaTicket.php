<?php

namespace App\Models;

use App\GroupTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotivoConsultaTicket extends Model
{
    protected $table = 'reason_tickets';


    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function group_ticket_onsite(): BelongsTo
    {
        return $this->belongsTo(GroupTicket::class,'group_ticket_id');
    }
}
