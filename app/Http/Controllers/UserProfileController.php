<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\User;
use Validator;

class UserProfileController extends Controller
{
    
    public function __construct(){
        $token = JWTAuth::parseToken()->authenticate();

        $this->user = $token->user_id;
    }

    public function checkUsername(Request $request){
        $id    = $request['keyId'];
        $value = $request['keyValue'];

        $count = User::where('username','=',$value)->count();

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

    public function checkPassword(Request $request){
        $id    = $request['keyId'];
        $value = $request['keyValue'];

        $getPassword = User::where('user_id','=',$this->user)->get()->first();

        if (Hash::check($value, $getPassword->password)) {
            $status = 200;
            $message = 'Password is correct';
        }
        else{
            $status = 403;
            $message = 'Password is incorrect';
        }

        return response()->json(compact('status','message'));
    }

    public function changeUsername(Request $request){

        $validator  = Validator::make($request->all(),[
                        'username'      =>  'required|unique:tblusers,username'
                      ]);

        if($validator->fails()){
            $status = 402;
            $message = 'Unprocessed Form Request';

            return response()->json(compact('status','message'),422);
        }

        $data       = [ 'username'  =>  $request['username']];

        User::where('user_id','=',$this->user)->update($data);

        $status     = 200;
        $message    = 'Username is changed';

        return response()->json(compact('status','message'));
    }

    public function changePassword(Request $request){
        
        $validator  = Validator::make($request->all(), [
            'password'          =>  'required',
            'newPassword'       =>  'required',
            'confirmPassword'   =>  'required'
        ]);

        if($validator->fails()){
            $status = 402;
            $message = 'Unprocessed Form Request';

            return response()->json(compact('status','message'),422);
        }

        $getPassword = User::where('user_id','=',$this->user)->get()->first();

        if (Hash::check($request['password'], $getPassword->password)) {

            $data = [ 'password'    =>  Hash::make($request['newPassword']) ];

            User::where('user_id','=',$this->user)->update($data);

            $status = 200;
            $message = 'Password is correct';
        }
        else{
            $status = 402;
            $message = 'Unprocessed Form Request';
        }

        return response()->json(compact('status','message'),$status);
    }
}
