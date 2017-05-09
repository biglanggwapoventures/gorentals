<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Mail;
use Session;

class ContactController extends Controller
{
    public function index() {
    	$name = '';
    	$email = '';
    	if(AUth::check()) {
    		$name = AUth::user()->fullname();
    		$email = Auth::User()->email;
    	}
    	return view('contact_us', compact(['name', 'email']));
    }
    public function postContact(Request $request) {
    	//dd($request->name);
    	$data = array(
    		'email'  	=> $request->email,
    		'to'		=> 'zmerzed@gmail.com',
    		'name'		=> $request->name,
    		'subject'	=> 'Feedback by: ' . $request->email,
    		'bodyMessage'	=> $request->message
    	);
    	Mail::send('email.contact_us', ['name'=>$request->name, 'subject'=> 'Feedback by: ' . $request->email, 'bodyMessage' => $request->message], function($message) use($data){
    		$message->from($data['email']);
    		$message->to($data['to']);
    		$message->subject($data['subject']);
    	});

    	Session::flash('success', "You're message has been sent..");
    	return redirect('/');
    }
}
