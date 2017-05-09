<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Property;
use App\Unit;
use App\User;
use Auth;
class Appointment extends Model
{
    protected $fillable = ['unit_id', 'user_id', 'date', 'status', 'schedule', 'user_seen_at'];

    public static function getAppointmentsCount($userId) {
    	$appointments = [];
        $user = User::find($userId);

	    if($user->login_type == 'USER') {
	        $appointments = DB::table('appointments')->where('user_id', Auth::user()->id)->where('status', '!=', 'PENDING')->get();
	        foreach($appointments as $key => $appointment) {
	            $unit = Unit::find($appointment->unit_id);
	            if($unit) {
	                $appointments[$key]->unit = $unit;
	                $appointments[$key]->property = Property::find($unit->property_id);
	                $appointments[$key]->address=$appointments[$key]->property->address;
	            }
	        }
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
        
      return count($appointments);
   	}


}
