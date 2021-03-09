<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcceptanceStatusLog extends Model
{
    protected $fillable = [
        'acceptance_id', 'status', 'user_id'
    ];

    public function acceptance()
    {
        return $this->belongsTo('App\Acceptance');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
