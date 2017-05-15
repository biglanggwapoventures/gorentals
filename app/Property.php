<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'address', 
        'latitude',
        'longitude',
        'building_name',
        'permit_number',
        'property_type',
        'gender',
        'capacity',
        'policy',
        'photos',
        'created_by',
        'extension'
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    public $types = [
        'BOARDING_HOUSE' => 'Boarding House',
        'APARTMENT' => 'Apartment',
        'DORMITORY' => 'Dormitory'
    ];
    
    public function units()
    {
        return $this->hasMany('App\Unit', 'property_id');
    }
    
    public function getTypeDescription()
    {
        switch($this->property_type){
            case 'BOARDING_HOUSE':  return 'Boarding House';
            case 'APARTMENT':  return 'Apartment';
            case 'DORMITORY':  return 'Dormitory';
            default: return null;
        }
    }

    public function owner()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
}
