<?php

namespace App\Http\Controllers;

use App\Baptism;
use Illuminate\Http\Request;
use App\Http\Requests\CreateBaptismRequest;



class BaptismController extends Controller
{

    public function index(Request $request)
    {
        

        $limit      = $request['limit'];
        $limitPage  = $request['page'] - 1;
        $offset     = $limit * $limitPage;
        $order      = strtoupper($request['order']);
        $field      = $request['field'];
        $filter     = $request['filter'];

        $count = Baptism::count();
        $data = Baptism::with('minister')->where($field, 'LIKE' , '%'.$filter.'%')->take($limit)->skip($offset)->orderBy($field, $order)->get();
        
        return response()->json([ 'status' => 200, 'count' => $count, 'data' => $data], 200);
    }


    public function store(CreateBaptismRequest $request)
    {
        Baptism::create($request->all());
        
        return response()->json([ 'status' => 200,'message' => 'Data Creation Success'], 200);
    }


    public function show($id)
    {
        $data    = Baptism::with('minister')->findOrFail($id);
        return response()->json($data, 200);
    }


    public function update(CreateBaptismRequest $request, Baptism $baptism)
    {
        $baptism->update($request->all());

        return response()->json(['status' => 200, 'message' => 'Data Update Success']);
    }



   


}
