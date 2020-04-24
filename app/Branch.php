<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'code', 'name',
    ];


    public function cso()
    {
        return $this->hasMany('App\Cso');
    }
    public function deliveryOrder()
    {
        return $this->hasMany('App\DeliveryOrder');
    }
    public function order()
    {
        return $this->hasMany('App\Order');
    }
}
