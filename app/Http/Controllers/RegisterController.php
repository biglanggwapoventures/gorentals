<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use Storage;
use Auth;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {

        $rules = [
            'firstname' => 'required|max:100',
            'lastname' => 'required|max:100',
            'username' => 'required|max:20|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'gender' => 'required|in:MALE,FEMALE',
            'mobile_number' => 'required|digits:11',
            'login_type' => 'required|in:USER,PROPERTY_OWNER',
            //'profile_picture' => 'required|image:max:2048',
            'status' => 'required'  
        ];

        $v = Validator::make($request->all(), $rules, [
            'login_type.in' => 'Please choose a your account type',
            'email.unique' => 'The email address you specified is already taken.',
        ]);

        if($v->fails()){
            return response()->json([
                'result' => false,
                'messages' => $v->errors()->all()
            ]);
        }

        $data = $request->only(array_keys($rules));
        $data['password'] = bcrypt($data['password']);
        unset($data['profile_picture']);

        $user = User::create($data);
        if($user->id){
            //$user->profile_picture = $request->file('profile_picture')->store("profile_pictures/{$user->id}", 'public');
            $user->save();

            Auth::login($user);

            return response()->json([
                'result' => true
            ]);
        }else{
            return response()->json([
                'result' => false,
                'messages' => ['An internal server error has occured while trying to make a new user. Please try again.']
            ]);
        }
        
    }
}
