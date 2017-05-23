<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PropertyRequest;
use App\Http\Requests\UnitRequest;
use App\Property;
use App\Unit;
use Auth;
use App\User;
use App\Appointment;
use DB;
use App\PropertyPolicy AS Policy; 
class PropertyOwnerController extends Controller
{
    public function showProfile()
    {
        
    }

    public function editProperty($id) {

        $property = Property::find($id);
        if(Auth::check() && Auth::user()->id == $property->created_by) {
           $propertyPolicies = $property->policyIds();
            return view('property-owner.edit', [
                'property'=> $property, 
                'genders' => ['MALE', 'FEMALE', 'BOTH'],
                'policies' => Policy::all(),
                'propertyPolicies' => $propertyPolicies
            ]);
        }

        return redirect('/');
    }

    public function createProperty()
    {
        return view('property-owner.create-property', [
            'genders' => ['MALE', 'FEMALE', 'BOTH'],
            'policies' => Policy::all()
        ]);
    }

    public function createUnit(Property $property)
    {
        $unit = new Unit;
        return view('property-owner.create-unit', [
            'property' => $property,
            'terms' =>  $unit->terms,
            'furnishing' => $unit->furnishings,
            'amenities' => $unit->amenitiesList,
        ]);
    }

    public function showProperties()
    {
        return view('property-owner.property-listing', [
            'properties' => Auth::user()->properties
        ]);
    }

    public function showPropertyUnits(Property $property, Request $request)
    {
        if($request->has('unit') && $request->has('status')) {
            $unit = Unit::find($request->input('unit'));
            $unit->status = $request->input('status');
            $unit->save();
        }
        return view('property-owner.units-listing', [
            'property' => $property,
            'units' => $property->units
        ]);
    }

    public function editUnit($property, $id)
    {
        $unit = Unit::find($id);
        $property = Property::find($property);
        return view('property-owner.edit-unit', [
            'property' => $property,
            'terms' =>  $unit->terms,
            'furnishing' => $unit->furnishings,
            'amenities' => $unit->amenitiesList,
            'unit' => $unit
        ]);    
    }
    public function showUnit($property, $id) {
        $unit = Unit::find($id);
        $property = Property::find($property);
        $isMyFavorite = false;
        $appointments = $unit->appointments();
        return view('property-owner.unit', [
            'unit' => $unit,
            'property' => $property,
            'isMyFavorite' => $isMyFavorite,
            'appointments' => $appointments
        ]);
    }
    public function updateProperty($id, Request $request)
    {
        $userId = Auth::id();
        $photos = ['primary', 'interior', 'bedrooms', 'bathrooms', 'amenities'];
        $data = $request->only([
            'address',
            'latitude',
            'longitude',
            'building_name',
            'permit_number',
            'property_type',
            'landmarks',
            'policy'
        ]);

        if($request->has('extension')) {
            $data['extension'] = $request->extension;
        }

        $data['created_by'] = $userId;
        $property = Property::find($id);


        if($data['property_type'] == 'DORMITORY' && $request->has('gender') && $request->has('capacity')) {
            $data['gender'] = $request->input('gender');
            $data['capacity'] = $request->input('capacity');
        } else {
            $data['gender'] = NULL;
            $data['capacity'] = NULL;
        }
        
        $property->update($data);
        $property->landmarks = $data['landmarks'];

        $uploaded = [];

        foreach($photos AS $p){
            for($x = 0; $x < 1; $x++){
                if($request->hasFile("photos.{$p}.{$x}")){

                    $uploaded[$p][$x] = $request->file("photos.{$p}.{$x}")->store("properties/{$userId}/{$property->id}/{$p}", 'public');
                    $property->photos = $uploaded;
                }
            }
        }

        
        $property->save();
        $property->policies()->sync($request->policies);

        return redirect('properties');
    }
    public function deleteProperty(Request $request) {
        if($request->has('property')) {
            //get units
            $property = Property::find($request->property);
            $units = DB::table('units')->where('property_id', $request->property)->get();
           // dd($units);
            foreach($units as $unit) {
                DB::table('appointments')->where('unit_id', $unit->id)->delete();
                DB::table('favorites')->where('unit', $unit->id)->delete();
            }
            DB::table('units')->where('property_id', $request->property)->delete();
            DB::table('properties')->where('id', $request->property)->delete();

        }
        return redirect('/properties');
    }
    public function deleteUnit(Request $request) {
        if($request->has('unit')) {
            //get units
            $unit = Unit::find($request->unit);
            $unitId  = $unit->id;
            $propertyId = $unit->property_id;
            DB::table('appointments')->where('unit_id', $request->unit)->delete();
            DB::table('favorites')->where('unit', $request->unit)->delete();
            DB::table('units')->where('id', $request->unit)->delete();
        }
        return redirect('/properties/'.$propertyId.'/units/');
    }

    public function storeProperty(PropertyRequest $request)
    {
        $userId = Auth::id();

         $photos = ['primary', 'interior', 'bedrooms', 'bathrooms', 'amenities'];

        $data = $request->only([
            'address',
            'latitude',
            'longitude',
            'building_name',
            'permit_number',
            'property_type',
            'landmarks',
            'policy'
        ]);

        if($request->has('building_name')) {
            $building_name = DB::table('properties')->where('building_name', $request->building_name)->first();  
        }

        if(!isset($building_name)) {
            if($data['property_type'] == 'DORMITORY' && $request->has('gender') && $request->has('capacity')) {
            $data['gender'] = $request->input('gender');
            $data['capacity'] = $request->input('capacity');
            } else {
                $data['gender'] = NULL;
                $data['capacity'] = NULL;
            }
            

            if($request->has('extension')) {
                $data['extension'] = $request->extension;
            }

            $data['created_by'] = $userId;

            $property = Property::create($data);
            $property->landmarks = $data['landmarks'];
            if(!$property->id){
                return response()->json([
                    'result' => false,
                    'messages' => ['An internal server error has occured while trying to create a new property. Please try again.']
                ]);
            }

            $uploaded = [];

            foreach($photos AS $p){
                for($x = 0; $x < 1; $x++){
                    if($request->hasFile("photos.{$p}.{$x}")){
                        $uploaded[$p][$x] = $request->file("photos.{$p}.{$x}")->store("properties/{$userId}/{$property->id}/{$p}", 'public');
                    }
                }
            }

            $property->photos = $uploaded;
            $property->update();

            $property->policies()->sync($request->policies);

            return response()->json([
                'result' => true
            ]);

        }
        
    }

    public function updatePropertyUnit($property, $id, UnitRequest $request)
    {
        $userId = Auth::id();
        $data = $request->only([
            'rental_terms',
            'long_term_minimum',
            'short_term_minimum',
            'long_term_rate',
            'short_term_daily_rate',
            'short_term_weekly_rate',
            'short_term_monthly_rate',
            'unit_number',
            'unit_floor',
            'furnishing',
            'bedrooms',
            'bathrooms',
            'inclusions',
            'amenities',
        ]);


        $data['created_by'] = $userId;

        $unit = Unit::find($id);
        $unit->update($data);
        

        // if($request->hasFile('photos')){
        //     $unit = Unit::find($id);
        //     $unit->photos = $request->file('photos')->store("properties/{$userId}/{$property->id}/units/{$unit->id}", 'public');
        //       dd($request->file('photos'));
        //     $unit->save();
        // }
        
        return redirect('/properties/'.$property.'/units');
    }

    public function storePropertyUnit(Property $property, UnitRequest $request)
    {
        $userId = Auth::id();


        $data = $request->only([
            'rental_terms',
            'long_term_minimum',
            'short_term_minimum',
            'long_term_rate',
            'short_term_daily_rate',
            'short_term_weekly_rate',
            'short_term_monthly_rate',
            'unit_number',
            'unit_floor',
            'furnishing',
            'bedrooms',
            'bathrooms',
            'inclusions',
            'amenities',
        ]);

        $data['created_by'] = $userId;
        $data['status'] = 1;
        $unit = $property->units()->create($data);

        if(!$unit->id){
            return response()->json([
                'result' => false,
                'messages' => ['An internal server error has occured while trying to create a new unit. Please try again.']
            ]);
        }

        if($request->hasFile('photos')){
            $unit->photos = $request->file('photos')->store("properties/{$userId}/{$property->id}/units/{$unit->id}", 'public');
            $unit->save();
        }
        
        return response()->json([
            'result' => true,
            'redirect' => '/properties/'.$property->id.'/units'
        ]);
    }
}
