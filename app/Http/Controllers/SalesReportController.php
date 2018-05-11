<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class SalesReportController extends Controller
{

    public function index(Request $request){
        $from       = Carbon::parse($request['from'])->toDateString();
        $to         = Carbon::parse($request['to'])->toDateString();
        $type       = $request['type'];
        $totalSales =   0;
        $data       =   [];

        if($type === 'byIndividual'){
            $items  = DB::table('services')->get();

            foreach($items as $item){
                $totalCost = 0;
                $sales = DB::table('serviceSales as ss')
                         ->leftJoin('serviceSaleItems as si','si.serviceSalesId','=','ss.serviceSalesId')
                         ->whereBetween('ss.dateIssued',[$from,$to])
                         ->where('ss.status','=',1)
                         ->where('si.serviceId','=',$item->serviceId)
                         ->get();
                foreach($sales as $sale){
                    $totalCost += ($sale->serviceCost * $sale->serviceQty);
                }
                $data[] = [ 'items' => $item, 'totalCost' => $totalCost ];
                $totalSales += $totalCost;
                $totalCost = 0;
            }
        }
        else{
                $categories  =   DB::table('serviceCategories')->orderBy('serviceCategoryName','ASC')->get();
                
                foreach($categories as $category){
                    $data [] = ['itemName' => $category->serviceCategoryName, 'totalCost' => '', 'isCategory' => true ];
                    $items  =   DB::table('services')->where('serviceCategoryId','=',$category->serviceCategoryId)->orderBy('serviceName','ASC')->get();
        
                    foreach($items as $item){
                        $totalCost  = 0;
                        $sales = DB::table('serviceSales as ss')
                        ->leftJoin('serviceSaleItems as si','si.serviceSalesId','=','ss.serviceSalesId')
                        ->whereBetween('ss.dateIssued',[$from,$to])
                        ->where('ss.status','=',1)
                        ->where('si.serviceId','=',$item->serviceId)
                        ->get();
        
                        foreach($sales as $sale){
                            $totalCost += ($sale->serviceCost * $sale->serviceQty);
                        }
                        $data [] = ['itemName' => $item->serviceCode.'-'.$item->serviceName , 'totalCost' => $totalCost, 'isCategory' => false ];
                        $totalSales += $totalCost;
                        $totalCost = 0;
                    }
                }

            }

        return response()->json(['data' => $data,'totalSales' => $totalSales]);
        
    }

}
