<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App;
class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        
        $rules = [
            'username' => 'required|exists:users',
            'password' => 'required'
        ];
        
        $v = Validator::make($request->all(), $rules, [
            'username.exists' => 'The username you entered does not belong to any account.',
        ]);

        if($v->fails()){
            return response()->json([
                'result' => false,
                'messages' => $v->errors()->all()
            ]);
        }

        $credentials = $request->only(array_keys($rules));
        $credentials['status'] = 'ENABLE';

        if(Auth::attempt($credentials)){
            $response = ['result' => true];

            if(Auth::user()->login_type === 'ADMIN') $response['next'] = url('admin');

            return response()->json($response);
        }

        $user = App\User::where('username', $request->input('username'))->first();

        if($user->status == 'DISABLE') {
            $messages = ['You\'re account has been disabled'];
        } else {
            $messages = ['Incorrect password'];
        }

        return response()->json([
             'result' => false,
             'messages' => $messages
        ]);

    }
}
