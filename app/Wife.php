<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Wife extends Model
{
    protected $table  = 'tblwifes';

    protected $primaryKey = 'wife_id';

  	protected $fillable = [
  		'wife_name',
  		'wife_father_name',
  		'wife_mother_name',
  		'wife_residence',
  		'wife_religion',
  		'wife_date_birth',

  	];


  	public function setWifeDateBirthAttribute ($date)

  	{

  		$this->attributes['wife_date_birth']	=	Carbon::parse($date);


  	}
}
