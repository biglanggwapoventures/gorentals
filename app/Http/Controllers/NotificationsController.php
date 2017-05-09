<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\User;


class NotificationsController extends Controller
{
    public function index()
    {
        return view('notifications', [
            'threads' => Auth::user()->getConversationList()
        ]);
    }

    public function sendMessage($partner, Request $request)
    {
        $v = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if($v->fails()){
            return response()->json([
                'result' => false,
                'messages' => $v->errors()->all()
            ]);
        }
        
        $result = Auth::user()->sentMessages()->create([
            'sent_to' => $partner,
            'message' => $request->message
        ]);
        
        if($result->id){
            return response()->json(['result' => true]);
        }

        return response()->json(['result' => false, 'messages' => ['Cannot send message.']], 500);
    }

    public function getConversation($partner)
    {
        $partnerInfo = User::find($partner);
         return view('notifications', [
             'messages' => Auth::user()->conversationWith($partner),
            'threads' => Auth::user()->getConversationList(),
            'currentThread' =>  $partner,
            'partnerInfo' => $partnerInfo
        ]);
    }
}
