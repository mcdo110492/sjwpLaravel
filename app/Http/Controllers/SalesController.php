<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use App\Sales;
use App\SalesItems;
use Validator;

class SalesController extends Controller
{
    //

    public function __construct(){
        $token = JWTAuth::parseToken()->authenticate();

        $this->user = $token->user_id;
    }

    public function index(Request $request){
        $limit      = $request['limit'];
        $limitPage  = $request['page'] - 1;
        $offset     = $limit * $limitPage;
        $order      = strtoupper($request['order']);
        $field      = $request['field'];
        $filter     = $request['filter'];
        $dateFilter = Carbon::parse($request['dateIssued'])->toDateString();

        $count = Sales::where('dateIssued','=',$dateFilter)->count();
        $data = Sales::with('users')
        ->where(function($q) use ($dateFilter) {
                 $q->where('dateIssued','=',$dateFilter);
        })
        ->where(function ($q) use ($filter,$field) {
             $q->where($field, 'LIKE' , '%'.$filter.'%');
        })
        ->take($limit)->skip($offset)->orderBy($field, $order)->get();
        
        return response()->json([ 'status' => 200, 'count'=> $count ,'data' => $data], 200);
    }

    public function totalCollection(){

        $now = Carbon::today();
        $where = ['dateIssued' => $now, 'status' => 1];
        $sales =  Sales::where($where)->get();
        $total  = 0;

        foreach($sales as $sale){
            $items = SalesItems::where('serviceSalesId','=',$sale->serviceSalesId)->get();
            foreach($items as $item){
                $total += ($item->serviceQty * $item->serviceCost);
            }
        }

        return response()->json(['collection' => $total, 'date' => $now]);
    }

    public function checkValue(Request $request){
        
        $count = Sales::where('rrNo','=',$request['keyValue'])->count();

        if($count>0){
            $status = 403;
            $message = 'Duplicate data entry';
        }
        else{
            $status = 200;
            $message = 'Data is available';
        }

        return response()->json(compact('status','message'));
    }

    public function store(Request $request){
        
        $details            = $request->details;
        $items              = $request->items;
        $serviceSalesId     = 0;
        DB::beginTransaction();
        
        $validateDetails    = Validator::make($details, [
            'rrNo'          =>  'required|unique:serviceSales,rrNo',
            'amountPaid'    =>  'required|numeric',
            'totalCost'     =>  'required|numeric'
        ]);
        
        if($validateDetails->fails()){
             DB::rollback();
            $response   =   ['message'=>'Form Details is not Complete.'];
            return response()->json($response,422);
        }
        else{
            $data1  = [
                'rrNo'          =>      $details['rrNo'],
                'amountPaid'    =>      $details['amountPaid'],
                'customer'      =>      $details['customer'],
                'totalCost'     =>      $details['totalCost'],
                'dateIssued'    =>      Carbon::now(),
                'user_id'       =>      $this->user
        
            ];
        
            $sales             = Sales::create($data1);
            $serviceSalesId    = $sales->serviceSalesId;
        }
        
        
        foreach($items as $item){
        
            $validateItems      =   Validator::make($item, [
                'serviceId'     =>  'required|integer',
                'cost'          =>  'required|numeric',
                'serviceQty'    =>  'required|numeric'
            ]);
        
            if($validateItems->fails()){
                DB::rollback();
                $response   =   ['message'=>'Form Items is not Complete.'];
                return response()->json($response,422);
                break;
            }
            else{
                $data2 = [
                    'serviceSalesId'     =>      $serviceSalesId,
                    'serviceId'          =>      $item['serviceId'],
                    'serviceCost'        =>      $item['cost'],
                    'serviceQty'         =>      $item['serviceQty']
                ];
        
                SalesItems::create($data2);
            }
        }
        
        DB::commit();
        $status         = 200;
        $message        = 'Data Creation Success';
        
        return response()->json(compact('status','message'));
    }

    public function changeStatus(Request $request){
        $serviceSalesId  = $request['serviceSalesId'];
        $status          = $request['status'];

        Sales::where('serviceSalesId','=',$serviceSalesId)->update(['status' => $status]);

        return response()->json(['status' => 200, 'message' => 'Status has been changed.']);
    }

    public function getItems($id){

        $serviceSalesId    = $id;
        $data              = SalesItems::with('serviceType')->where('serviceSalesId','=',$serviceSalesId)->get();
        
        return response()->json(compact('data'));
    }

    public function print(Request $request){

        $dateFilter = Carbon::parse($request['dateIssued'])->toDateString();
        $netCost    = 0;

        $data = Sales::with('users')
        ->where(function($q) use ($dateFilter) {
                 $q->where('dateIssued','=',$dateFilter)->where('status','=',1);
        })
        ->orderBy('rrNo', 'ASC')->get();

        foreach($data as $sale){
            $netCost += $sale->totalCost;
        }
        
        return response()->json([ 'status' => 200, 'netCost'=> $netCost ,'data' => $data], 200);
    }

}
