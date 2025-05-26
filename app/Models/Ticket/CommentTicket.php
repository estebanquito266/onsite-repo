<?php

namespace App\Models\Ticket;

use App\Models\Admin\User;
use App\Models\Cliente\Cliente;
use App\Models\Onsite\EmpresaOnsite;
use App\Models\User as ModelsUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentTicket extends Model
{
    protected $table = 'comment_tickets';
    protected $fillable = ['ticket_id','user_comment_id','cliente_id','comentario','archivo'];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class,'ticket_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(ModelsUser::class,'user_comment_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(EmpresaOnsite::class,'cliente_id');
    }
}
