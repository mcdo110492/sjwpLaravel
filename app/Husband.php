<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Husband extends Model
{
    protected $table  = 'tblhusbands';

    protected $primaryKey = 'husband_id';

  	protected $fillable = [
  		'husband_name',
  		'husband_father_name',
  		'husband_mother_name',
  		'husband_residence',
  		'husband_religion',
  		'husband_date_birth',

  	];


  	public function setHusbandDateBirthAttribute ($date)

  	{

  		$this->attributes['husband_date_birth']	=	Carbon::parse($date);

  		
  	}





}
