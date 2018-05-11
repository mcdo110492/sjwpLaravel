<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Baptism;
use App\Confirmation;
use App\Death;
use App\Marriage;

class ExcelImportController extends Controller
{
    
     public function importExcel ($source, Baptism $baptism, Confirmation $confirmation, Marriage $marriage, Death $death)

    {

       if (request()->hasFile('userFile')) 

        {
            $file  = request()->file('userFile');
            $size  = $file->getClientSize();
            $ext   = $file->guessClientExtension();

            if($ext === 'xlsx' && $size <= 5000000 )
            {
                $path = $file->store('excel');
                if($file->isValid())
                {
                    $contents = storage_path('app/').$path;

                    $results = Excel::load($contents, function($reader) { })->toArray();
                     
                        $minister  = request()->minister_id;
                        if($source === 'baptism')
                        {
                            $response  = $baptism->addBaptism($results,$minister);
                        }
                        else if ($source === 'confirmation')
                        {
                            $response = $confirmation->addConfirmation($results,$minister);
                        }
                        else if($source === 'marriage')
                        {
                            $response = $marriage->addMarriageBulk($results,$minister);
                            
                        }
                        else if($source === 'death')
                        {
                            $response = $death->addDeath($results,$minister);
                        }
                        else {
                            $response = ['status'=>404,'msg'=>'Not Found.'];
                        }

                        
                    
                    return response()->json($response);
                }
                else
                {
                    return response()->json(['status' => 500]);
                }

            }
            else
            {
                return response()->json(['status' => 500]);
            }
        }

        else
        {

            return response()->json(['status' => 500]);

        } 
        
        
       
    }

}
