<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use App\User;

class AuthenticateController extends Controller
{


    public function authenticate(Request $request)
    {
    	
        $credentials = $request->only('username', 'password');

        try {
            $where          = [ 'username' => $credentials['username'], 'password' => $credentials['password'], 'status' => 1 ];

            $user           = User::where('username',$where['username'])->get()->first();

            $role           = $user['role'];

            $customClaim    = [ 'role' => $role];

            $profileName    = $user['profile_name'];

            $profilePic     = $user['img_profile'];

            $status         = 200;


            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($where,$customClaim) )

            {

                return response()->json([ 'status' => 401,'error' => 'Invalid Credentials'], 200);

            }

        } catch (JWTException $e) {


            // If the token creation has an error.
            return response()->json([ 'status' => 500, 'error' => 'Token Creation Error'], 500);


        }

        // if no errors are encountered we can return a JWT
        return response()->json(compact('token','profileName','profilePic','role','status'));
      

        
    }


    public function checkValidity ()

    {

        $status    = 200;
        return response()->json(compact('status'),$status);

    }


}
