<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unit;
use Auth;
use App\Appointment;
use DB;
use Session;

class UnitController extends Controller
{
    public function show($unit)
    {
    	$isMyFavorite = false;
        $alreadyAppointment = true;
    	if(Auth::check()) {
    		$unitFavorite = DB::table('favorites')->where('user', Auth::user()->id)->where('unit', $unit)->first();
    		if($unitFavorite) {
    			$isMyFavorite = true;
    		}
            $alreadyAppointment = DB::table('appointments')->where('unit_id', $unit)->where('user_id', Auth::user()->id)->where('status', 'ACCEPT')->get();
            if(count($alreadyAppointment) >= 1) {
                $alreadyAppointment = true;
            } else {
                $alreadyAppointment = false;
            }

            if(Auth::user()->isAdmin()) {
                $alreadyAppointment = true;
            } else if(Auth::user()->login_type == 'PROPERTY_OWNER') {
                $alreadyAppointment = true;
            }

            $pending = DB::table('appointments')->where('unit_id', $unit)->where('user_id', Auth::user()->id)->where('status', 'PENDING')->get();
            if(count($pending) >= 1) {
                $alreadyAppointment = true;
            }
    	}
        

        $unit = Unit::where('id', $unit)->with('property')->first();
        return view('view-unit', [
            'unit' => $unit,
            'isMyFavorite' => $isMyFavorite,
            'alreadyAppointment' => $alreadyAppointment
        ]);
    }
    public function addFavorite(Request $request) {
    	if(Auth::check() && $request->has('unit')) {
            // check 3 limit favortes
            $favs = DB::table('favorites')->where('user', Auth::user()->id)->get(); 
            if(count($favs) >= 3) {
                Session::flash('message', "You only allowed 3 limits for favorites");
                return redirect('/units/'.$request->input('unit').'/view');
            } 
            else {
                DB::table('favorites')->insert(['user'=>Auth::user()->id, 'unit'=>$request->input('unit')]);
                return redirect('/units/'.$request->input('unit').'/view');
            }
    		
    	}
    	return redirect('/');
    }
    public function removeFavorite(Request $request){

    	if(Auth::check() && $request->has('unit')) {
    		DB::table('favorites')->where('unit', $request->input('unit'))->where('user', Auth::user()->id)->delete();
    		return redirect('/?fav=true');
    	}
    	return redirect('/');
    }
    public function test() {
        $unit = Unit::find(1);
        dd($unit->appointments()->toArray());
    }
}
