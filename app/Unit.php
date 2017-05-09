<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Unit extends Model
{
    public $terms = [
        'LONG' => [
            '6_MONTHS' => '6 months',
            '1_YEAR_OR_MORE' => '1 year or more'
        ],
        'SHORT' => [
            '3_MONTHS' => '3 months', 
            '2_MONTHS' => '2 months', 
            '1_MONTH' => '1 month', 
            '3_WEEKS' => '3 weeks', 
            '2_WEEKS' => '2 weeks', 
            '1_WEEK' => '1 week', 
            '6_NIGHTS' => '6 nights', 
            '5_NIGHTS' => '5 nights', 
            '4_NIGHTS' => '4 nights', 
            '3_NIGHTS' => '3 nights', 
            '2_NIGHTS' => '2 nights', 
        '   1_NIGHTS' => '1 night', 
        ]
    ];

    public $furnishings = [
        'FULLY_FURNISHED' => 'Fully furnished',
        'SEMI_FURNISHED' => 'Semi furnished',
        'UNFURNISHED' => 'Unfurnished',
    ];

    public $amenitiesList = [
        'Balcony', 
        'Function Room',
        'Jogging Hall',
        'Swimming Pool',
        'Parking',
        '24/7 Security',
        'With Aircon',
        'Without Aircon',
        'Concierge',
        'Storage Space',
        'Study Hall',
        'Gym',
        'Pets Allowed'
    ];

    protected $casts = [
        // 'photos' => 'array',
        'amenities' => 'array'
    ];

    protected $fillable = [
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
        'photos',
        'created_by',
        'status'
    ];

    protected $dates = [
        'approved_at'
    ];

    public function property()
    {
        return $this->belongsTo('App\Property', 'property_id');
    }
    public function user() {
        return $this->belongsTo('App\User', 'created_by');
    }
    public function appointments() {
        $units = DB::table('appointments')->where('unit_id', $this->id)->get();
        foreach($units as $key => $value) {
            $user = User::find($value->user_id);
            $units[$key]->username = $user->fullname();
        }
        return $units;
    }
    public function isAvailable() {
        if(is_null($this->status)) return false;
        return $this->status ? true : false;
    }
    public function allPhotos()
    {
        $propertyPhotos = array_flatten($this->property->photos);
        $propertyPhotos[] = $this->photos;
        return $propertyPhotos ;
    }

    public function scopePendingApproval($query)
    {
        return $query->whereNull('approved_at');
    }

    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_at');
    }
}

