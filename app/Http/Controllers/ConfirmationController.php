<?php

namespace App\Http\Controllers;

use App\Confirmation;
use Illuminate\Http\Request;
use App\Http\Requests\ConfirmationRequest;

class ConfirmationController extends Controller
{
   
    public function index(Request $request)
    {
        

        $limit      = $request['limit'];
        $limitPage  = $request['page'] - 1;
        $offset     = $limit * $limitPage;
        $order      = strtoupper($request['order']);
        $field      = $request['field'];
        $filter     = $request['filter'];

        $count = Confirmation::count();
        $data = Confirmation::with('minister')->where($field, 'LIKE' , '%'.$filter.'%')->take($limit)->skip($offset)->orderBy($field, $order)->get();
        
        return response()->json(compact('count','data'), 200);
    }

   
    public function create()
    {

        return response()->json([ 'status' => 404,'message' => 'Page Not Found.'], 404);
    }

  
    public function store(ConfirmationRequest $request)
    {
        $confirmation = Confirmation::create($request->all());
        
        return response()->json([ 'status' => 200,'message' => 'Data Creation Success'], 200);
    }

 
    public function show($id)
    {
        $data    = Confirmation::with('minister')->findOrFail($id);

        return response()->json($data, 200);
    }

  
    public function edit()
    {
       return response()->json([ 'status' => 404,'message' => 'Page Not Found.'], 404);
    }

   
    public function update(ConfirmationRequest $request, Confirmation $confirmation)
    {
        $confirmation->update($request->all());

        return response()->json(['status' => 200 , 'message' => 'Data Update Success']);
    }

   
    public function destroy()
    {
        
        return response()->json([ 'status' => 404,'message' => 'Page Not Found.'], 404);
    }
}
