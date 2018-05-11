<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'serviceSales';

    protected $primaryKey = 'serviceSalesId';

    protected $fillable = [
        'serviceSalesId',
        'rrNo',
        'amountPaid',
        'totalCost',
        'dateIssued',
        'status',
        'customer',
        'user_id'
    ];

    public function users (){
        return $this->belongsTo('App\User','user_id');
    }
}
