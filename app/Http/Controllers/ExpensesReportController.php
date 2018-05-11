<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpensesReportController extends Controller
{

    public function index(Request $request){
        $from       = Carbon::parse($request['from'])->toDateString();
        $to         = Carbon::parse($request['to'])->toDateString();
        $type       = $request['type'];
        $netCosts =   0;
        $data       =   [];

        if($type === 'byIndividual'){
            $items  = DB::table('expenses')->get();

            foreach($items as $item){
                $totalCost = 0;
                $sales = DB::table('expenseCosts')->whereBetween('dateExpense',[$from,$to])->where('expenseId','=',$item->expenseId)->where('status','=',1)->get();
                foreach($sales as $sale){
                    $totalCost += ($sale->expenseCost);
                }
                $data[] = [ 'items' => $item, 'totalCost' => $totalCost ];
                $netCosts += $totalCost;
                $totalCost = 0;
            }
        }
        else{
                $categories  =   DB::table('expenseCategories')->orderBy('expenseCategoryName','ASC')->get();
                
                foreach($categories as $category){
                    $data [] = ['itemName' => $category->expenseCategoryName, 'totalCost' => '', 'isCategory' => true ];
                    $items  =   DB::table('expenses')->where('expenseId','=',$category->expenseCategoryId)->orderBy('expenseName','ASC')->get();
        
                    foreach($items as $item){
                        $totalCost  = 0;
                        $sales = DB::table('expenseCosts')
                        ->whereBetween('dateExpense',[$from,$to])
                        ->where('status','=',1)
                        ->where('expenseId','=',$item->expenseId)
                        ->get();
        
                        foreach($sales as $sale){
                            $totalCost += ($sale->expenseCost);
                        }
                        $data [] = ['itemName' => $item->expenseCode.'-'.$item->expenseName , 'totalCost' => $totalCost, 'isCategory' => false ];
                        $netCosts += $totalCost;
                        $totalCost = 0;
                    }
                }

            }

        return response()->json(['data' => $data,'netCosts' => $netCosts]);
        
    }

}
