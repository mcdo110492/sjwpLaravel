<?php

namespace App\Http\Controllers;

use App\Minister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\MinisterRequest;

class MinisterController extends Controller
{

    public function index(Request $request)
    {
        

        $limit      = $request['limit'];
        $limitPage  = $request['page'] - 1;
        $offset     = $limit * $limitPage;
        $order      = strtoupper($request['order']);
        $field      = $request['field'];
        $filter     = $request['filter'];

        $count = Minister::count();
        $data = Minister::where($field, 'LIKE' , '%'.$filter.'%')->take($limit)->skip($offset)->orderBy($field, $order)->get();
        
         return response()->json([ 'status' => 200, 'count'=> $count ,'data' => $data], 200);
    }


    public function create()
    {

        return response()->json([ 'status' => 404,'message' => 'Page Not Found.'], 404);
    }

    public function store(MinisterRequest $request)
    {
        Minister::create($request->all());
        
        return response()->json([ 'status' => 200,'message' => 'Data Creation Success'], 200);
    }


    public function show($id)
    {
        $data    = Minister::findOrFail($id);

        return response()->json($data, 200);
    }

    public function all(){
        
        $data    = Minister::all();
        
        return response()->json([ 'data' => $data], 200);
    }

    public function active(){
        
        $data = Minister::where('status','=',1)->get()->first();

        return response()->json([ 'data' => $data], 200);
    }


    public function update(Request $request, Minister $minister)
    {
        $minister->update($request->all());

        return response()->json(['status' => 200, 'message' => 'Data Update Success'], 200);
    }

    public function changeStatus(Request $request){

        $minister_id        = $request['minister_id'];
        $status             = $request['status'];

        DB::beginTransaction();

        $count              = DB::table('tblministers')->where('status','=',$status)->count();
        
        if($count>0){

            $minister                 = DB::table('tblministers')->where('status','=',$status)->get()->first();

            DB::table('tblministers')->where('minister_id','=',$minister->minister_id)->update([ 'status' => 0 ]);
        }
        
        $upd = DB::table('tblministers')->where('minister_id','=',$minister_id)->update([ 'status' => 1 ]);

        if($upd){
            DB::commit();
            $response = ['status' => 200, 'message' => 'Update Success'];
        }
        else{
            DB::rollBack();
            $response = ['status' => 500, 'message' => 'Update Failed'];
        }

        return response()->json($response,200);

    }

  
}
