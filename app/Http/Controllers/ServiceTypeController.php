<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ServiceTypeRequest;

use App\ServiceType;

class ServiceTypeController extends Controller
{
    public function index(Request $request)
    {
        

        $limit      = $request['limit'];
        $limitPage  = $request['page'] - 1;
        $offset     = $limit * $limitPage;
        $order      = strtoupper($request['order']);
        $field      = $request['field'];
        $filter     = $request['filter'];

        $count = ServiceType::count();
        $data = ServiceType::with('serviceCategory')->where($field, 'LIKE' , '%'.$filter.'%')->orWhere('serviceCode','LIKE','%'.$filter.'%')->take($limit)->skip($offset)->orderBy($field, $order)->get();
        
        return response()->json([ 'status' => 200, 'count' => $count, 'data' => $data], 200);
    }

    public function getServices(Request $request){

        $filter     = $request['filter'];
        $data       = ServiceType::with('serviceCategory')
                      ->where('serviceName','LIKE','%'.$filter.'%')
                      ->orWhere('serviceCode','LIKE','%'.$filter.'%')
                      ->orderBy('serviceName','ASC')
                      ->get();

        return response()->json($data);
    }

    public function checkValue(Request $request){
        $id    = $request['keyId'];
        $value =  strtoupper($request['keyValue']);

        //Check if the id is 0 determine that this item is newly created
        if($id == 0){
            $count = ServiceType::where('serviceCode','=',$value)->count();
            
                    if($count>0){
                        $status = 403;
                        $message = 'Duplicate data entry';
                    }
                    else{
                        $status = 200;
                        $message = 'Data is available';
                    }
        }
        //Else if the item is to be updated
        else {
            //check if the item has a value with the same id set the status to 200 because this is a valid
            $count = ServiceType::where('serviceCode','=',$value)->where('serviceId','=',$id)->count();
            if($count>0){
                $status = 200;
                $message = 'Data is available';
            }
            else{
                //except the value is not the same with the id check the value again if is valid or not
                $count2 = ServiceType::where('serviceCode','=',$value)->count();
                
                        if($count2>0){
                            $status = 403;
                            $message = 'Duplicate data entry';
                        }
                        else{
                            $status = 200;
                            $message = 'Data is available';
                        }
            }
        }
        

        return response()->json(compact('status','message'));

    }

    public function store(ServiceTypeRequest $request) {
        
        ServiceType::create($request->all());
                
        return response()->json([ 'status' => 200,'message' => 'Data Creation Success'], 200);
    }
        
    
    public function show($id) {
        
        $data = ServiceType::findOrFail($id);
        
        return response()->json($data, 200);
    }   
        
    public function update(ServiceTypeRequest $request, $id){
        
        $data = [
            'serviceCode'      =>  strtoupper($request['serviceCode']),
            'serviceName'      =>  $request['serviceName'],
            'cost'             =>  $request['cost'],
            'serviceCategoryId'=>  $request['serviceCategoryId']
        ];
        
        ServiceType::where('serviceId','=',$id)->update($data);
                
        return response()->json(['status' => 200, 'message' => 'Data Update Success'], 200);
        
    }
}
