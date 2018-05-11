<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;

class Death extends Model
{
    protected $table		=	'tbldeaths';
    protected $primaryKey	=	'death_id';

    protected $fillable		=	[
    	'deceased_name',
    	'residence',
    	'date_death',
    	'place_burial',
    	'date_burial',
    	'book_no',
    	'page_no',
    	'entry_no',
    	'minister_id',
    ];

    public function minister()

    {

     	return $this->belongsTo('App\Minister','minister_id');

    }


    public function setDateDeathAttribute ($date)

    {

     	$this->attributes['date_death']	=	Carbon::parse($date);

    }

    public function setDateBurialAttribute ($date)

    {

     	$this->attributes['date_burial']	=	Carbon::parse($date);

    }

    public function addDeath($request,$minister)

    {
    	   DB::beginTransaction();
		   $count = 1 ;
		   $err = 0;
		   $numberErr = [];
           foreach($request as $r)

		   {
			   $count++;
			   $validator = Validator::make($r, [
                            'deceased_name'     => 'required',
                            'date_of_death'     => 'required',
                            'residence'         => 'required',
                            'date_of_burial'    => 'nullable|date',
                            'place_of_burial'   => 'nullable',
                            'book_no'           => 'required|integer',
                            'page_no'           => 'required|integer',
                            'entry_no'          => 'required|integer'
                        ]);

				$data = [
					'deceased_name'	=>	$r['deceased_name'],
					'residence'	    =>	$r['residence'],
					'date_death'	=>	$r['date_of_death'],
					'place_burial'	=>	$r['place_of_burial'],
					'date_burial'	=>	$r['date_of_burial'],
					'book_no'		=>	$r['book_no'],
					'page_no'		=>	$r['page_no'],
					'entry_no'		=>	$r['entry_no'],
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
