<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCompany extends Model
{
    protected $table = "user_companies";

    protected $fillable = ['user_id', 'company_id'];

    // RELACIONES
    public function user()
    {
        return $this->belongsTo('App\Models\Admin\User');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Admin\Company');
    }
}
