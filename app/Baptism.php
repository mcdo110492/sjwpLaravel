<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;

class Baptism extends Model
{
    
    protected $table  = 'tblbaptisms';

    protected $primaryKey = 'baptism_id';

  	protected $fillable = [
  		'child_name',
  		'father_name',
  		'mother_name',
  		'birth_place',
  		'birthday',
  		'baptism_date',
  		'book_no',
  		'page_no',
  		'entry_no',
  		'sponsors',
  		'remarks',
  		'minister_id'

  	];


    public function minister()

    {

     	return $this->belongsTo('App\Minister','minister_id');

    }


    public function setBirthdayAttribute ($date)

    {

     	$this->attributes['birthday']	=	Carbon::parse($date);

    }

    public function setBaptismDateAttribute ($date)

    {

     	$this->attributes['baptism_date']	=	Carbon::parse($date);

    }

	public function addBaptism($request,$minister)

    {
    	   DB::beginTransaction();
		   $count = 1 ;
		   $err = 0;
		   $numberErr = [];
           foreach($request as $r)

		   {
			   $count++;
			   $validator = Validator::make($r, [
                            'child_name'    => 'required',
                            'father_name'   => 'required',
                            'mother_name'   => 'required',
                            'birth_place'   => 'nullable',
                            'birthday'      => 'nullable|date',
                            'baptism_date'  => 'nullable|date',
                            'book_no'       => 'required|integer',
                            'page_no'       => 'required|integer',
                            'entry_no'      => 'required|integer',
                            'sponsors'      => 'nullable',
                            'remarks'       => 'nullable'
                        ]);

				$data = [
					'child_name'	=>	$r['child_name'],
					'father_name'	=>	$r['father_name'],
					'mother_name'	=>	$r['mother_name'],
					'birth_place'	=>	$r['birth_place'],
					'birthday'		=>	$r['birthday'],
					'baptism_date'	=>	$r['baptism_date'],
					'book_no'		=>	$r['book_no'],
					'page_no'		=>	$r['page_no'],
					'entry_no'		=>	$r['entry_no'],
					'sponsors'		=>	$r['sponsors'],
					'remarks'		=>	$r['remarks'],
					'minister_id'	=>	$minister
				];

				if($validator->fails())
                {
					$err++;
					$numberErr [] = $count;
					
                }
                else
                {
                   
                    $this::create($data);
                }
                       

		   }

		    if($err == 0)
			{
				DB::commit();
				return ['status' => 200];
				
			}
			else
			{
				DB::rollBack();
				return ['status' => 403, 'errors' => $numberErr];
			}
    

    }

}
