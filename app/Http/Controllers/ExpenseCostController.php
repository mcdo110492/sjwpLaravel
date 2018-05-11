<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExpenseCostRequest;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\ExpenseCost;
use Carbon\Carbon;

class ExpenseCostController extends Controller
{
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
        $dateExpense = Carbon::parse($request['dateExpense'])->toDateString();

        $count = ExpenseCost::where('dateExpense','=',$dateExpense)->count();
        $data = ExpenseCost::with('users','expenses')
        ->where(function($q) use ($dateExpense) {
                 $q->where('dateExpense','=',$dateExpense);
        })
        ->where(function ($q) use ($filter,$field) {
             $q->where($field, 'LIKE' , '%'.$filter.'%');
        })
        ->take($limit)->skip($offset)->orderBy($field, $order)->get();
        
        return response()->json([ 'status' => 200, 'count'=> $count ,'data' => $data], 200);
    }

    public function getDetails($id){

        $data = ExpenseCost::findOrFail($id);

        return response()->json($data);

    }

    public function store(ExpenseCostRequest $request){
        $data = [
            'expenseId'     =>  $request['expenseId'],
            'expenserrNo'   =>  $request['expenserrNo'],
            'expenseCost'   =>  $request['expenseCost'],
            'dateExpense'   =>  Carbon::parse($request['dateExpense'])->toDateString(),
            'details'       =>  $request['details'],
            'status'        =>  1,
            'user_id'       =>  $this->user

        ];

        ExpenseCost::create($data);

        $status         = 200;
        $message        = 'Data Creation Success';
        
        return response()->json(compact('status','message'));
    }

    public function update(ExpenseCostRequest $request, $id){
        $data = [
            'expenseId'     =>  $request['expenseId'],
            'expenserrNo'   =>  $request['expenserrNo'],
            'expenseCost'   =>  $request['expenseCost'],
            'dateExpense'   =>  Carbon::parse($request['dateExpense'])->toDateString(),
            'details'       =>  $request['details']
        ];

        ExpenseCost::where('expenseCostId','=',$id)->update($data);
        
        $status         = 200;
        $message        = 'Update Success';
                
        return response()->json(compact('status','message'));
    }

    public function changeStatus(Request $request){
        $data = [
            'status'        =>  $request['status']
        ];

        ExpenseCost::where('expenseCostId','=',$request['expenseCostId'])->update($data);

        $status         = 200;
        $message        = 'Update Success';

        return response()->json(compact('status','message'));
    }

    public function print(Request $request){
        
        $dateExpense = Carbon::parse($request['dateExpense'])->toDateString();
        $netCost    = 0;
        
        $data = ExpenseCost::with('expenses')
        ->where(function($q) use ($dateExpense) {
                    $q->where('dateExpense','=',$dateExpense)->where('status','=',1);
        })
        ->orderBy('expenserrNo', 'ASC')->get();
        
        foreach($data as $cost){
            $netCost += $cost->expenseCost;
        }
                
        return response()->json([ 'status' => 200, 'netCost'=> $netCost ,'data' => $data], 200);
    }
}
