<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    protected $table        = 'services';

    protected $primaryKey   = 'serviceId';

    protected $fillable     =  [
        'serviceCode',
        'serviceName',
        'cost',
        'serviceCategoryId'
    ];


    public function serviceCategory () {

        return $this->belongsTo('App\ServicesCategories','serviceCategoryId');
        
    }

    public function setServiceCodeAttribute($value){
        $this->attributes['serviceCode'] = strtoupper($value);
    }
}
