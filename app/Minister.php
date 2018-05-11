<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Minister extends Model
{
    //

    protected $table  = 'tblministers';
    protected $primaryKey = 'minister_id';

    protected $fillable = [
   		'minister_name', 'status'
    ];
}
