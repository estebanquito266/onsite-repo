<?php

namespace App\Models\Ticket;

use App\Enums\TicketType;
use App\Models\Onsite\ReparacionOnsite;

use App\Models\User;
use App\Models\Cliente\Cliente;
use App\Models\Reparacion\Reparacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\CategoryTicket;
use App\Enums\TicketType as EnumsTicketType;
use App\GroupTicket;
use App\Models\Derivacion\ClienteDerivacion;
use App\Models\Derivacion\Derivacion;
use App\Models\MotivoConsultaTicket;
use App\Models\Onsite\EmpresaOnsite;
use App\Models\Ticket\StatusTicket;
use App\Models\Ticket\PriorityTicket;
use DateTime;
class Ticket extends Model
{
    protected $table = 'tickets';
    protected $fillable = ['company_id','type','reparacion_id','derivacion_id','cliente_id','group_user_receiver_id', 'cliente_derivacion_id',
                        'category_ticket_id','status_ticket_id','file','priority_ticket_id','detail','reason_ticket_id','user_receiver_id','user_owner_id','expiration_date'];

    const SEMAFORODIAS = 3;
    public function reparacion(): BelongsTo
    {
        return $this->belongsTo(ReparacionOnsite::class,'reparacion_id');
    }

    public function user_receiver(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_receiver_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(EmpresaOnsite::class,'cliente_id');
    }


    public function user_owner(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_owner_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(CommentTicket::class,'ticket_id');
    }

    public function motivo_consulta(): BelongsTo
    {
        return $this->belongsTo(MotivoConsultaTicket::class,'reason_ticket_id');
    }

    public function group_user_receiver(): BelongsTo
    {
        return $this->belongsTo(GroupTicket::class,'group_user_receiver_id');
    }

    public function category_ticket(): BelongsTo
    {
        return $this->belongsTo(CategoryTicket::class,'category_ticket_id');
    }

    public function status_ticket(): BelongsTo
    {
        return $this->belongsTo(StatusTicket::class,'status_ticket_id');
    }

    public function priority_ticket(): BelongsTo
    {
        return $this->belongsTo(PriorityTicket::class,'priority_ticket_id');
    }

    public function getTypeName()
    {
        return EnumsTicketType::getDescription($this->type);
    }

    static function checkTicketReparacionExists($reparacion_id,$category_ticket_id,$user_owner_id,$company_id,$ticket_id = null){
        if($ticket_id){
            $result = Ticket::where([['reparacion_id',$reparacion_id],['category_ticket_id',$category_ticket_id],['user_owner_id',$user_owner_id],['company_id',$company_id],['id','!=',$ticket_id]])->get();
        }else{
            $result = Ticket::where([['reparacion_id',$reparacion_id],['category_ticket_id',$category_ticket_id],['user_owner_id',$user_owner_id],['company_id',$company_id]])->get();
        }
                    
        if(count($result)>0)
            return true;
        return false;
    }

    public function getSemaforoAttribute()
    {
        $semaforo = "";
        if($this->expiration_date){
            date_default_timezone_set('UTC');
            $fecha_vto2 = $this->expiration_date;
            $fecha_actual = date("d-m-Y");
            $fecha_vto1 = date("d-m-Y",strtotime($fecha_vto2."- ".$this::SEMAFORODIAS." days"));
            
            $fecha_vto1_date = new DateTime($fecha_vto1);
            $fecha_actual_date = new DateTime($fecha_actual);
            $fecha_vto2_date = new DateTime($fecha_vto2);
    
            $semaforo = "A tiempo";
            if($fecha_vto2_date < $fecha_actual_date){
                $semaforo = "Vencido";
            }else{
                if($fecha_vto1_date < $fecha_actual_date){
                    $semaforo = "Por vencer";
                }
            }
        }
        
        return $semaforo;  
    }

    public function getSemaforoclassAttribute()
    {
        $semclass = "";
        switch ($this->semaforo) {
            case 'Vencido':
                $semclass = "sem-gradient-danger";
                break;
            case 'Por vencer':
                $semclass = "sem-gradient-warning";
                break;
             case 'A tiempo':
                $semclass = "sem-gradient-success";
                break;
        }
        return $semclass;  
    }
}
