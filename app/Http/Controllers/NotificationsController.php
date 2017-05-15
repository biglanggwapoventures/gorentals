<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\User;
use DB;
use App\Unit;
use App\Property;


class NotificationsController extends Controller
{
    public function index()
    {
         $appointments = [];
        if(Auth::check()) {
            if(Auth::user()->login_type == 'USER') {
                $appointments = DB::table('appointments')->where('user_id', Auth::user()->id)->where('status', '!=', 'PENDING')->get();
                foreach($appointments as $key => $appointment) {
                    $unit = Unit::find($appointment->unit_id);
                    if($unit) {
                        $appointments[$key]->unit = $unit;
                        $appointments[$key]->property = Property::find($unit->property_id);
                        $appointments[$key]->address=$appointments[$key]->property->address;
                    }
                }
                Auth::user()->clearAppointmentNotification();
            } else {
                $properties = DB::table('properties')->where('created_by', Auth::user()->id)->get();
                $units = array();
                foreach($properties as $property) {
                    $tmp_units = DB::table('units')->where('property_id', $property->id)->get();
                    foreach($tmp_units as $unit) {
                        $units[] = $unit;
                    }
                }

                $appointments = array();
                foreach($units as $unit) {
                    $t_appointments = DB::table('appointments')->where('unit_id', $unit->id)->get();
                    foreach($t_appointments as $appointment) {
                        $appointments[] = $appointment;
                    }
                }
                foreach($appointments as $key => $value) {
                    $appointments[$key]->user = User::find($value->user_id);
                    $sUnit = Unit::find($value->unit_id);
                    $appointments[$key]->unit = $sUnit;
                    $appointments[$key]->property = Property::find($sUnit->property_id);
                    $appointments[$key]->address= $sUnit ->property->address;
                }
             //  dd($appointments);
            }
        }
        $notificationCount = Auth::check() ? Auth::user()->unreadMessagesCount() : 0;
        $appointmentCount = Auth::user()->getAppointmentsCount();
        return view('notifications', [
            'threads' => Auth::user()->getConversationList(),
            'notificationCount' => $notificationCount,
            'appointmentCount' => $appointmentCount,
            'appointments' => $appointments
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
        $notificationCount = Auth::check() ? Auth::user()->unreadMessagesCount() : 0;
        $appointmentCount = Auth::user()->getAppointmentsCount();

        $appointments = [];
        if(Auth::check()) {
            if(Auth::user()->login_type == 'USER') {
                $appointments = DB::table('appointments')->where('user_id', Auth::user()->id)->where('status', '!=', 'PENDING')->get();
                foreach($appointments as $key => $appointment) {
                    $unit = Unit::find($appointment->unit_id);
                    if($unit) {
                        $appointments[$key]->unit = $unit;
                        $appointments[$key]->property = Property::find($unit->property_id);
                        $appointments[$key]->address=$appointments[$key]->property->address;
                    }
                }
                Auth::user()->clearAppointmentNotification();
            } else {
                $properties = DB::table('properties')->where('created_by', Auth::user()->id)->get();
                $units = array();
                foreach($properties as $property) {
                    $tmp_units = DB::table('units')->where('property_id', $property->id)->get();
                    foreach($tmp_units as $unit) {
                        $units[] = $unit;
                    }
                }

                $appointments = array();
                foreach($units as $unit) {
                    $t_appointments = DB::table('appointments')->where('unit_id', $unit->id)->get();
                    foreach($t_appointments as $appointment) {
                        $appointments[] = $appointment;
                    }
                }
                foreach($appointments as $key => $value) {
                    $appointments[$key]->user = User::find($value->user_id);
                    $sUnit = Unit::find($value->unit_id);
                    $appointments[$key]->unit = $sUnit;
                    $appointments[$key]->property = Property::find($sUnit->property_id);
                    $appointments[$key]->address= $sUnit ->property->address;
                }
             //  dd($appointments);
            }
        }
       
        // $view->with('notificationCount', $count);
         return view('notifications', [
            'messages' => Auth::user()->conversationWith($partner),
            'threads' => Auth::user()->getConversationList(),
            'currentThread' =>  $partner,
            'partnerInfo' => $partnerInfo,
            'notificationCount' => $notificationCount,
            'appointmentCount' => $appointmentCount,
            'appointments' => $appointments
        ]);
    }
}
