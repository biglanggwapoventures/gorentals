<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unit;
use Carbon\Carbon;
use App\User;

class AdminController extends Controller
{
   public function index(Request $request)
   {      

       if($request->has('approved')) {
        $units = Unit::Approved()->with('property')->get();
        $label = 'Approved Units';
       } else {
        $units = Unit::pendingApproval()->with('property')->get();
        $label = 'Pending Units';
       }
       return view('admin.home', [
           'units' => $units,
           'label_unit' => $label
       ]);
   }

    public function users(Request $request)
   {
       $type = $request->type == 'owners' ? 'PROPERTY_OWNER' : 'USER';

       $users = User::ofType($type)->orderBy('lastname');
       if($request->has('name') && trim($request->name)){
            $users->whereRaw("CONCAT(firstname, ' ', lastname) LIKE '%{$request->name}%'");
       }
       if($request->has('gender') && in_array($request->gender, ['MALE', 'FEMALE'])){
            $users->where('gender', $request->gender);
       }
       if($request->has('enable') && $request->has('user')) {
          User::changeStatus($request->input('user'), $request->input('enable'));
       }
       
       $user_type_desc = $request->type ==  'owners' ? 'Property Owners' : 'Standard Users';

       $url_params = '';
       if($request->has('type')) {
          $url_params = '?type='.$request->input('type') . '&';
       }
       

       return view('admin.users', [
           'users' => $users->get(),
           'user_type' => $user_type_desc,
           'type' => $request->type,
           'url_params' => $url_params
       ]);
   }

   public function approveUnit(Unit $unit)
   {
        $unit->approved_at = Carbon::now();
        $unit->save();
        return response()->json([
            'result' => true
        ]);
   }


}
