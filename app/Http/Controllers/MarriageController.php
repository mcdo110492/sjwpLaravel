<?php

namespace App\Http\Controllers;

use App\Marriage;
use Illuminate\Http\Request;
use App\Http\Requests\MarriageRequest;

class MarriageController extends Controller
{

    public function __construct(Marriage $marriage) {
        

        $this->marriage = $marriage;
        
    }

    public function index(Request $request)
    {
        

        $limit      = $request['limit'];
        $limitPage  = $request['page'] - 1;
        $offset     = $limit * $limitPage;
        $field      = $request['field'];
        $filter     = $request['filter'];
        $order      = strtoupper($request['order']);

        $count = Marriage::count();

        $data = $this->marriage->queryMarriage($limit,$limitPage,$offset,$field,$filter,$order);

       

        return response()->json(compact('count','data'),200);
    }



    public function store(MarriageRequest $request)
    {

        $this->marriage->addMarriage($request);
        
        return response()->json([ 'status' => 200,'message' => 'Data Creation Success'], 200);
    }


    public function show($id)
    {
        $data    = $this->marriage->getMarriage($id);

        return response()->json($data, 200);
    }

   

    public function update(MarriageRequest $request, $id)
    {
        $this->marriage->updateMarriage($request);

        return response()->json(['status' => 200, 'message' => 'Data Update Success']);
    }



}
