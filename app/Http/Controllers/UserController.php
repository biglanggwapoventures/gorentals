<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Appointment;
use Auth;
use Validator;
use DB;
use App\Property;
use App\Unit;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index($id) {
    	$user = User::find($id);
    	return view('profile', ['user'=>$user]);
    }
    public function profile() {
    	if(Auth::check()) {
            $img = Auth::user()->profile_picture;
    		return view('current_profile', compact(['img']));
    	}
        return redirect('/');
    }
    public function edit() {
    	if(Auth::check()) {
    		return view('edit_profile');
    	}
        return redirect('/');
    }
    public function appointments()
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

        return view('appointments', compact(['appointments']));
    }


    public function _setAppointment($id, Request $request) {
        if(Auth::check()) {
            // check appointment
            $appointments = Appointment::where('unit_id', $id)->get();
            $canSet = true;
            if($appointments)
            {
                foreach($appointments as $appointment) {
                    if((\Carbon\Carbon::parse($appointment->date)->format('Y/m/d') == \Carbon\Carbon::parse($request->input('date'))->format('Y/m/d'))) {
                        $canSet = false;
                    }
                }
            }
            if($canSet) {
                Appointment::create([
                    'user_id'=>Auth::user()->id, 
                    'unit_id'=>$id, 
                    'date'=>$request->input('date'),
                    'status'=> 'PENDING'
                ]); 
            } else {
                $request->session()->flash('message', 'This schdule ' . \Carbon\Carbon::parse($request->input('date'))->format('Y/m/d') . ' has already been scheduled');
            } 
        }
        return redirect('/units/'.$id.'/view');
    }

    public function setAppointment($unitId, Request $request)
    {
        if(!Auth::check()) abort(403);

        $clientDateFormat = 'm/d/Y g:i A';
        $schedule = Carbon::createFromFormat($clientDateFormat, $request->schedule, 'Asia/Manila')->addSeconds(59);
        
        if($request->schedule != $schedule->format($clientDateFormat)){
            return response()->json(['result' => false, 'message' => 'Invalid date and time format given.']);
        }

        $tomorrow = Carbon::now('Asia/Manila')->addHours(24);
        if($tomorrow->gt($schedule)){
            return response()->json([
                'result' => false, 
                'message' => 'Schedule must be at least one day from now'
            ]);
        }

        $scheduleFormatted = $schedule->format('Y-m-d H:i:s');
        $conflicts = Appointment::select('id')
            ->whereUnitId($unitId)
            ->where('status', '!=', 'DECLINE')
            ->whereRaw("'{$scheduleFormatted}' BETWEEN schedule AND DATE_ADD(schedule, INTERVAL 3 HOUR)")
            ->count('id');

        if($conflicts > 0){
            return response()->json(['result' => false, 'message' => 'Your selected schedule is in conflict with other users.']);
        }

        Appointment::create([
            'user_id' => Auth::id(), 
            'unit_id' => $unitId, 
            'date' => $request->schedule,
            'schedule' => $scheduleFormatted,
            'status' => 'PENDING'
        ]); 

        return response()->json(['result' => true]);

    }

    public function updateAppointment($id, $flag) {
        if(Auth::check()) {
           $appointment = Appointment::find($id);
           if((int) $flag >= 1) {
                $appointment->status = 'ACCEPT';
           } else {
                $appointment->status = 'DECLINE';
           }
           $appointment->remarks = request()->remarks;
           $appointment->save();
        }
        // return redirect('/appointments');
        return redirect(url('/notifications?tab=appointments'));
    }

    
    public function deleteAppointment($id){
        $unit = Unit::find($id);
        DB::table('appointments')->where('id', $id)->delete();
        return redirect('/properties/'.$unit->property->id.'/units/'.$unit->id.'/view');
    }


    public function update(Request $request) {
        if(Auth::check()) {
            $rules = [
                'firstname' => 'required|max:100',
                'lastname' => 'required|max:100',
                'username' => 'required|max:20|unique:users',
                'email' => 'required|email|max:255|unique:users',
                'gender' => 'required|in:MALE,FEMALE',
                'mobile_number' => 'required|digits:11',
            ];

            $v = Validator::make($request->all(), $rules, [
                'login_type.in' => 'Please choose a your account type',
                'email.unique' => 'The email address you specified is already taken.',
            ]);
            
            $data = $request->only(array_keys($rules));
            User::where('id', Auth::user()->id)->update($data);

            if($request->hasFile('profile_picture')) {  
                $user = User::find(Auth::user()->id);
                $user->profile_picture = $request->file('profile_picture')->store("profile_pictures/{$user->id}", 'public');
                $user->save();
            }
            return redirect('profile');
        }

        
        return redirect('/');
    }
    public function changepassword(Request $request) {
        if(!Auth::check()) { return redirect('/'); }
        $messages = [];

        if($request->has('user_id')) {
            if($request->has('old_password') && $request->has('password') && $request->has('rpassword')) {
                if(Auth::attempt(['email'=>Auth::user()->email, 'password'=>$request->input('old_password')])) {
                    if($request->input('password') != $request->input('rpassword')) {
                        $messages = ["New password and Repeat Password does not match"];
                    } else {
                        $user = User::find(Auth::user()->id);
                        $user->password = bcrypt($request->input('password'));
                        $user->save();
                        return redirect('profile');
                    }
                    
                } else {
                    $messages = ["You're old password is invalid"];
                }
            } else {
                $messages = ['Please input the following fields'];
            }
        }
        return view('change_password', compact(['messages']));
    }
}
