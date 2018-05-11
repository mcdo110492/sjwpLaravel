<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Husband;
use App\Wife;
use Validator;

class Marriage extends Model
{
    //
    protected $table  = 'tblmarriages';

    protected $primaryKey = 'marriage_id';

  	protected $fillable = [
  		'husband_id',
  		'wife_id',
  		'date_married',
  		'sponsors',
  		'book_no',
  		'page_no',
  		'entry_no',
  		'remarks',
  		'minister_id'

  	];

    public function minister ()

    {

        return $this->belongsTo('App\Minister','minister_id');

    
    }

    public function husband ()

    {


        return $this->belongsTo('App\Husband','husband_id');


    }


    public function wife ()

    {

        return $this->belongsTo('App\Wife','wife_id');


    }

  	public function queryMarriage ($limit,$limitPage,$offset,$field,$filter,$order)

  	{

  		$query 		= DB::table('tblmarriages as tm')
  					  ->leftJoin('tblministers as m','m.minister_id','=','tm.minister_id')
  					  ->leftJoin('tblhusbands as h','h.husband_id','=','tm.husband_id')
  					  ->leftJoin('tblwifes as w','w.wife_id','=','tm.wife_id')
  					  ->where('h.'.$field, 'LIKE' , '%'.$filter.'%')
  					  ->orderBy('h.'.$field, $order)
  					  ->take($limit)
  					  ->skip($offset)
  					  ->get();


  		return $query;

    }


    public function getMarriage($marriage_id){

        $query 		= DB::table('tblmarriages as tm')
        ->leftJoin('tblministers as m','m.minister_id','=','tm.minister_id')
        ->leftJoin('tblhusbands as h','h.husband_id','=','tm.husband_id')
        ->leftJoin('tblwifes as w','w.wife_id','=','tm.wife_id')
        ->where('tm.marriage_id', '=' , $marriage_id)
        ->get()->first();


        return $query;
    }
      



    public function setDateMarriedAttribute ($date)

    {

     	$this->attributes['date_married']	=	Carbon::parse($date);

    }


    public function addMarriage ($request)

    {
    	DB::transaction(function () use ($request) {
            $husband      = [
                'husband_name'              =>  $request->husband_name,
                'husband_father_name'       =>  $request->husband_father_name,
                'husband_mother_name'       =>  $request->husband_mother_name,
                'husband_residence'         =>  $request->husband_residence,
                'husband_religion'          =>  $request->husband_religion,
                'husband_date_birth'        =>  $request->husband_date_birth
            ];

            $wife      = [
                'wife_name'              =>  $request->wife_name,
                'wife_father_name'       =>  $request->wife_father_name,
                'wife_mother_name'       =>  $request->wife_mother_name,
                'wife_residence'         =>  $request->wife_residence,
                'wife_religion'          =>  $request->wife_religion,
                'wife_date_birth'        =>  $request->wife_date_birth
            ];
            $husbandCreate  =   Husband::create($husband);

            $wifeCreate     =   Wife::create($wife);

            $marriage   =   [
                'date_married'          => $request->date_married,
                'sponsors'              => $request->sponsors,
                'book_no'               => $request->book_no,
                'page_no'               => $request->page_no,
                'entry_no'              => $request->entry_no,
                'remarks'               => $request->remarks,
                'minister_id'           => $request->minister_id,
                'husband_id'            => $husbandCreate->husband_id,
                'wife_id'               => $wifeCreate->wife_id

            ];

            $marriageCreate  = $this::create($marriage);
        });

    }

    public function updateMarriage ($request)

    {
    	DB::transaction(function () use ($request) {
            $husband      = [
                'husband_name'              =>  $request->husband_name,
                'husband_father_name'       =>  $request->husband_father_name,
                'husband_mother_name'       =>  $request->husband_mother_name,
                'husband_residence'         =>  $request->husband_residence,
                'husband_religion'          =>  $request->husband_religion,
                'husband_date_birth'        =>  $request->husband_date_birth
            ];

            $husbandId 						=	$request->husband_id;

            $husbandUpdate  =   Husband::find($husbandId)->update($husband);

            $wife      = [
                'wife_name'              =>  $request->wife_name,
                'wife_father_name'       =>  $request->wife_father_name,
                'wife_mother_name'       =>  $request->wife_mother_name,
                'wife_residence'         =>  $request->wife_residence,
                'wife_religion'          =>  $request->wife_religion,
                'wife_date_birth'        =>  $request->wife_date_birth
            ];
            
            $wifeId 				     =   $request->wife_id;

            $wifeUpdate     =   Wife::find($wifeId)->update($wife);

            $marriage   =   [
                'date_married'          => $request->date_married,
                'sponsors'              => $request->sponsors,
                'book_no'               => $request->book_no,
                'page_no'               => $request->page_no,
                'entry_no'              => $request->entry_no,
                'remarks'               => $request->remarks,
                'minister_id'           => $request->minister_id

            ];

            $marriageId 				= $request->id;

            $marriageUpdate  = $this::find($marriageId)->update($marriage);
        });

    }


    public function addMarriageBulk ($request,$minister)

    {
    	   DB::beginTransaction();
		   $count = 1 ;
		   $err = 0;
		   $numberErr = [];
           foreach($request as $r)

		   {
			   $count++;
			   $validator = Validator::make($r, [
                            'husband_name'                  =>  'required',
                            'husband_father_name'           =>  'required',
                            'husband_mother_name'           =>  'required',
                            'husband_residence'             =>  'nullable',
                            'husband_date_of_birth'         =>  'nullable|date',
                            'husband_religion'              =>  'nullable',
                            'wife_name'                     =>  'required',
                            'wife_father_name'              =>  'required',
                            'wife_mother_name'              =>  'required',
                            'wife_residence'                =>  'nullable',
                            'wife_date_of_birth'            =>  'nullable|date',
                            'wife_religion'                 =>  'nullable',
                            'date_of_marriage'              =>  'required|date',
                            'book_no'                       =>  'required|integer',
                            'page_no'                       =>  'required|integer',
                            'entry_no'                      =>  'required|integer',
                            'sponsors'                      =>  'nullable'
                        ]);

				$husbandData = [
					'husband_name'	            =>	$r['husband_name'],
					'husband_father_name'	    =>	$r['husband_father_name'],
					'husband_mother_name'	    =>	$r['husband_mother_name'],
					'husband_residence'	        =>	$r['husband_residence'],
					'husband_date_birth'	    =>	$r['husband_date_of_birth'],
					'husband_religion'	        =>	$r['husband_religion']
				];

                $wifeData = [
					'wife_name'	                =>	$r['wife_name'],
					'wife_father_name'	        =>	$r['wife_father_name'],
					'wife_mother_name'	        =>	$r['wife_mother_name'],
					'wife_residence'	        =>	$r['wife_residence'],
					'wife_date_birth'	        =>	$r['wife_date_of_birth'],
					'wife_religion'	            =>	$r['wife_religion']
				];

				if($validator->fails())
                {
					$err++;
					$numberErr [] = $count;
					
                }
                else
                {
                   
                    $husbandCreate  =   Husband::create($husbandData);

                    $wifeCreate     =   Wife::create($wifeData);

                    $marriageData   =   [
                        'date_married'          => $r['date_of_marriage'],
                        'sponsors'              => $r['sponsors'],
                        'book_no'               => $r['book_no'],
                        'page_no'               => $r['page_no'],
                        'entry_no'              => $r['entry_no'],
                        'minister_id'           => $minister,
                        'husband_id'            => $husbandCreate->husband_id,
                        'wife_id'               => $wifeCreate->wife_id
                    ];

                    $marriageCreate  = $this::create($marriageData);
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
