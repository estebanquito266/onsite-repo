<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\GroupTicket;
use App\Models\Admin\User;

class UserGroupTicket extends Model
{
    protected $table = 'user_group_ticket';
    protected $fillable = ['company_id','user_id','group_ticket_id'];


}
