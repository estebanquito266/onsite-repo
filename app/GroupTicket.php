<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\User;

class GroupTicket extends Model
{
    protected $table = "group_tickets";

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_group_ticket', 'group_ticket_id', 'user_id')->withPivot('id');
    }
}
