<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicesCategories extends Model
{
    protected $table = 'serviceCategories';

    protected $primaryKey = 'serviceCategoryId';

    protected $fillable = [
        'serviceCategoryName'
    ];
}
