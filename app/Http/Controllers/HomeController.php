<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Property;
use App\Unit;
use Auth;
use Lang;
use DB;
class HomeController extends Controller
{
    protected $barangays = [
        'Adlawon',
        'Agsungot',
        'Apas',
        'Bacayan',
        'Banilad',
        'Binaliw',
        'Budla-an',
        'Busay',
        'Cambinocot',
        'Capitol Site',
        'Carreta',
        'Cogon Ramos',
        'Day-as',
        'Ermita',
        'Guba',
        'Hipodromo',
        'Kalubihan',
        'Kamagayan',
        'Kamputhaw (Camputhaw)',
        'Kasambagan',
        'Lahug',
        'Lorega San Miguel',
        'Lusaran',
        'Luz',
        'Mabini',
        'Mabolo',
        'Malubog',
        'Pahina Central',
        'Pari-an',
        'Paril',
        'Pit-os',
        'Pulangbato',
        'Sambag 1',
        'Sambag 2',
        'San Antonio',
        'San José',
        'San Roque',
        'Santa Cruz',
        'Santo Niño',
        'Sirao',
        'T. Padilla',
        'Talamban',
        'Taptap',
        'Tejero',
        'Tinago',
        'Zapatera',
        'Tabogon',
        'Babag',
        'Banawa',
        'Basak Pardo',
        'Basak San Nicolas',
        'Bonbon',
        'Buhisan',
        'Bulacao Pardo',
        'Buot-Taup Pardo',
        'Calamba',
        'Cogon Pardo',
        'Duljo-Fatima',
        'Guadalupe',
        'Inayawan',
        'Kalunasan',
        'Kinasang-an Pardo',
        'Labangon',
        'Mambaling',
        'Pahina San Nicolas',
        'Pamutan',
        'Pasil',
        'Poblacion Pardo',
        'Pung-ol-Sibugay',
        'Punta Princesa',
        'Quiot Pardo',
        'San Nicolas Proper',
        'Sapangdaku',
        'Sawang Calero',
        'Sinsin',
        'Suba San Nicolas',
        'Sudlon I',
        'Sudlon II',
        'Tabunan',
        'Tag-bao',
        'Tisa',
        'To-ong Pardo',
        'Tuburan',

    ];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        $property = new Property;
        $unit = new Unit;

        $units = Unit::select();
        $hasFields = false;
        $units->whereHas('property', function ($query) USE ($request, $property) {
            
            // if($request->has('address') && trim($request->address))  {
            //     // dd('sadasd');
            //     $query->where('address', 'like', "%{$request->address}%");
            // }
                

            if($request->has('property_type') && in_array($request->property_type, array_keys($property->types))){
                // dd('aaa');
                 $query->where('property_type', '=', $request->property_type);
            }
               

            if($request->has('gender')) {
                // dd('asdsad');
                $query->where('gender', '=', $request->gender);
            }
        });

       

        if($request->has('furnishing') && in_array($request->furnishing, array_keys($unit->furnishings))) 
            $units->where('furnishing', '=', $request->furnishing);

        if($request->has('terms') && in_array($request->terms, ['LONG', 'SHORT']))
            $units->where('rental_terms', '=', $request->terms);

        if($request->has('bedrooms') && is_numeric($request->bedrooms))
            $units->where('bedrooms', '=', $request->bedrooms);

        if($request->has('bathrooms') && is_numeric($request->bathrooms))
            $units->where('bathrooms', '=', $request->bathrooms);

        if($request->has('capacity') && is_numeric($request->capacity))
            $units->where('bedrooms', '=', $request->capacity);

        if($request->has('min_price') && is_numeric($request->min_price)){
            $units->where(function($query) USE ($request) {
                if($request->has('terms') && $request->terms === 'LONG'){
                    $query->where('long_term_rate', '>=', $request->min_price);
                }

                if($request->has('terms') && $request->terms === 'SHORT'){
                    $query->where('short_term_daily_rate', '>=', $request->min_price)
                    ->orWhere('short_term_weekly_rate', '>=', $request->min_price)
                    ->orWhere('short_term_monthly_rate', '>=', $request->min_price);
                }   
            });
        }

        if($request->has('max_price') && is_numeric($request->max_price)){
            $units->where(function($query) USE ($request) {
                if($request->has('terms') && $request->terms === 'LONG'){
                    $query->where('long_term_rate', '<=', $request->max_price);
                }

                if($request->has('terms') && $request->terms === 'SHORT'){
                    $query->where('short_term_daily_rate', '<=', $request->max_price)
                    ->orWhere('short_term_weekly_rate', '<=', $request->max_price)
                    ->orWhere('short_term_monthly_rate', '<=', $request->max_price);
                }   
            });
        }

        if(in_array($postSort = $request->input('post_time'), ['asc', 'desc'])){
            $units->orderBy('created_at', $postSort);
        }

        if(in_array($priceSort = $request->input('price_sort'), ['asc', 'desc'])){
            $units->orderBy(DB::raw("CASE WHEN rental_terms = 'LONG' THEN long_term_rate ELSE short_term_weekly_rate END", $priceSort));
        }

        $favorites = array();
        if($request->has('fav')) {
            $tfavorites = DB::table('favorites')->select('unit')->where('user',Auth::id())->get()->pluck('unit');
            $byFav = true;
         //   dd($favorites);
            $f_units = $units->approved()->whereIn('id', $tfavorites)->with('property')->orderBy('id', 'DESC')->limit(3)->get();
            // dd($f_units);
        }else{
            $byFav = false;
            $f_units = $units->approved()->with('property')->paginate(15);
        }
       

      //  dd($f_units);
        $sug_units = false;
        if(count($f_units) <= 0) {
             $sug_units = Unit::limit(3)->get();
        }
        $brgys = lang::get('barangay');
        return view('home', [
           'types' => $property->types,
           'furnishing' => $unit->furnishings,
           'amenities' => $unit->amenitiesList,
           'units' => $f_units,
           'brgys' => $brgys['brgys'],
           'byFav' => $byFav,
           'favorites' => $favorites,
           'sug_units' => $sug_units,
           'barangays' => $this->barangays,
           'permitMove' => (int)empty($request->all())
        ]);
    }
}
