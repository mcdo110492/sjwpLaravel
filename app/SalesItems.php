<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesItems extends Model
{
    protected $table = 'serviceSaleItems';

    protected $primaryKey = 'serviceSalesId';

    protected $fillable = [
        'serviceSaleItemId',
        'serviceId',
        'serviceCost',
        'serviceQty',
        'serviceSalesId'
    ];

    public function serviceType(){
        return $this->belongsTo('App\ServiceType','serviceId');
    }

}
