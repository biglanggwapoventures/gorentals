@extends('layouts.app')

   
@section('content')
 <!-- Header Property Map -->
  @if (Session::has('success'))
       <div class="alert alert-info">{{ Session::get('success') }}</div>
    @endif
    
    <header id="banner" class="stat_bann" @if($byFav) style="display: none" @endif>
        <div class="bannr_sec2">
            <div id="map"></div>
            <div class="clearfix"></div>
        </div>
    </header>
    <!-- Page Content -->
    <section id="srch_slide" @if($byFav) style="display: none" @endif >

        <div class="container">

            <!-- Search Form -->
            <div class="row">
                <div class="col-md-12">
                    <div class="srch_frm">
                        <h3>unit Search</h3>
                        <form name="sentMessage" id="contactForm" novalidate>
                            <p>
                                <label for="amount">Search Radius</label>
                                <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
                                <input type="hidden" value="{{ request()->input('search_radius', 1500) }}" name="search_radius">
                                <input type="hidden" name="search_lat" value="{{ request()->input('search_lat') }}" />
                                <input type="hidden" name="search_lng" value="{{ request()->input('search_lng') }}" />
                            </p>
                            
                            <div id="slider"></div>
                             <br>
                            <div class="control-group form-group">
                                <div class="controls col-md-3 first">
                                    <label>Barangay </label>
                                    <!--<input id="searchTextField" type="text" class="form-control" name="address" value="{{ request()->address }}"> -->
                                     <select name="address" class="form-control">
                                     <option></option>
                                     @foreach($barangays as $b)
                                         <option value="{{$b}}">{{$b}}</option>
                                     @endforeach
                                   </select> 
                                </div>

                                <div class="controls col-md-3">
                                     <label>Property Type </label>
                                        <select name="property_type" class="form-control">
                                        <option ></option>
                                         @foreach($types AS $val => $label)
                                            <option value="{{ $val }}" {{request()->property_type == $val ? 'selected' : ''}}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="controls col-md-3">
                                    <label>Min. Price </label>
                                    <input name="min_price" type="text" class="form-control" value="{{ request()->min_price }}">
                                </div>
                                <div class="controls col-md-3">
                                    <label>Max. Price </label>
                                    <input name="max_price" type="text" class="form-control" value="{{ request()->max_price }}">
                                </div>

                                
                                <div class="clearfix"></div>
                            </div>

                            <div class="control-group form-group">
                                <div class="controls col-md-3 first">
                                    <label>Bedrooms </label>
                                    @php
                                        $bedrooms = array_combine(range(1, 5), range(1, 5))
                                    @endphp
                                    <select name="bedrooms" class="form-control" >
                                        <option></option>
                                        @foreach($bedrooms AS $val => $label)
                                            <option value="{{ $val }}"  {{request()->bedrooms == $val ? 'selected' : ''}}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="controls col-md-3">
                                    <label>Bathrooms </label>
                                    @php
                                        $bathrooms = array_combine(range(1, 5), range(1, 5))
                                    @endphp
                                    <select name="bathrooms" class="form-control" >
                                        <option></option>
                                        @foreach($bathrooms AS $val => $label)
                                            <option value="{{ $val }}" {{request()->bathrooms == $val ? 'selected' : ''}}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="controls col-md-3">
                                    <label>Furnishing </label>
                                    <select name="furnishing"class="form-control">
                                        <option></option>
                                        @foreach($furnishing AS $val => $label)
                                            <option value="{{ $val }}" {{request()->furnishing == $val ? 'selected' : ''}}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="controls col-md-3">
                                    <label>Terms </label>
                                    @php
                                        $types = ['LONG' => 'Long Term (6 monhts or more)', 'SHORT' => 'Short Term (A few nights or weeks)']
                                    @endphp
                                    <select name="terms" class="form-control" >
                                        <option></option>
                                        @foreach($types AS $val => $label)
                                            <option value="{{ $val }}" {{request()->terms == $val ? 'selected' : ''}}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                
                                 <div class="controls col-md-3 first">
                                    <label>Capacity <i>(for dormitory only)</i></label>
                                    <input name="capacity" type="text" class="form-control" value="{{ request()->capacity }}">
                                </div>
                                <div class="controls col-md-3 ">
                                    <label>Gender <i>(for dormitory only)</i></label>
                                    <select name="gender" class="form-control" >
                                        <option></option>
                                        <option value="MALE">MALE</option>
                                        <option value="FEMALE">FEMALE</option>
                                        <option value="BOTH">BOTH</option>
                                    </select>
                                </div>
                                <div class="controls col-md-3 ">
                                    <label>Sort by price</label>
                                    <select name="price_sort" class="form-control" >
                                        <option></option>
                                        <option value="asc" {{ request()->price_sort === 'asc' ? 'selected' : '' }}>Lowest to highest</option>
                                        <option value="desc" {{ request()->price_sort === 'desc' ? 'selected' : '' }}>Higest to lowest</option>
                                    </select>
                                </div>
                                <div class="controls col-md-3 ">
                                    <label>Sort by post time</label>
                                    <select name="post_time" class="form-control" >
                                        <option></option>
                                        <option value="desc" {{ request()->post_time === 'desc' ? 'selected' : '' }}>Most Recent</option>
                                        <option value="asc" {{ request()->post_time === 'asc' ? 'selected' : '' }}>Oldest</option>
                                    </select>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="control-group form-group">
                                @foreach(array_chunk($amenities, (int)count($amenities)/3) AS $col)
                                <div class="col-sm-3">
                                    @foreach($col AS $a)
                                      <div class="checkbox">
                                        <label><input type="checkbox" name="amenities[]"  value="{{$a}}" {{ in_array($a, (array)request()->amenities) ? 'checked' : ''}}>{{$a}}</label>
                                      </div>
                                    @endforeach
                                </div>
                                @endforeach 
                                <div class="clearfix"></div>
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                            <div class="clearfix"></div>
                            <div id="success"></div>
                            <!-- For success/fail messages -->
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container -->

    </section>

    <div class="spacer-60"></div>

    <!-- Featured Properties Section -->
    <section id="feat_propty" data-permit="{{ $permitMove }}">
        <div class="container">
            <div class="row">
                <div class="titl_sec">
                    <div class="col-xs-6">
                        @if($byFav)
                            <h3 class="main_titl text-left">MY FAVORITES</h3>
                        @else
                            <h3 class="main_titl text-left">AVAILABLE UNITS</h3>
                        @endif

                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="results-section">
                    @forelse($units->chunk(3) AS $row)
                        <div class="row">
                        @foreach($row AS $unit)
                            @if ($unit->user->isEnable())
                                @if($unit->isAvailable())
                                <div class="col-md-4 rental-feed hidden">
                                    <div class="panel panel-default unit-item result" data-lat="{{ $unit->property->latitude }}" data-lng="{{ $unit->property->longitude }}" data-address="{{ $unit->property->address }}">
                                    <div class="panel-image tile" style="background-image:url(@if(isset($unit->property->photos['primary'])) {{ asset("storage/{$unit->property->photos['primary'][0]}") }}@endif);background-size: cover;background-repeat: no-repeat;background-position: center;">
                                        <img style="display:none;" class="img-responsive img-hover" src="@if(isset($unit->property->photos['primary'])) {{ asset("storage/{$unit->property->photos['primary'][0]}") }}@endif" alt="">
                                        <div class="img_hov_eff">
                                            <a class="btn btn-default btn_trans" href="{{ route('view-unit', ['unit' => $unit->id]) }}"> More Details </a>
                                        </div>
                                        <div class="sal_labl">
                                            Vacant
                                        </div>
                                    </div>

                                    <div class="rental-home-feed panel-body">
                                        <div class="prop_feat">
                                            <p class="area"><i class="fa fa-home"></i> {{  $unit->property->getTypeDescription() }}</p>
                                            <p class="bedrom"><i class="fa fa-bed"></i> {{ $unit->bedrooms }} Bed(s)</p>
                                            <p class="bedrom"><i class="fa fa-bath"></i> {{ $unit->bathrooms }} Bath(s)</p>
                                        </div>
                                        <h3 class="sec_titl">{{ $unit->property->building_name }}</h3>

                                        <p class="sec_desc">
                                            @if($unit->property->extension) {{$unit->property->extension}} - @endif {{ $unit->property->address }}
                                            <br>
                                            <span class="nearby-the-property"> Landmark: {{ $unit->property->landmarks }} </span>
                                            <table class="table table-condensed">
                                                <tr>
                                                    <td>Rental Term</td>
                                                    <td class="text-right"><strong>{{ $unit->rental_terms === 'LONG' ? 'Long Term' :  'Short Term' }}</strong></td>
                                                    @if($unit->rental_terms === 'LONG')
                                                        <tr>
                                                            <td>Rate</td>
                                                            <td class="text-right"><strong>Php {{ number_format($unit->long_term_rate, 2) }}</strong></td>
                                                        </tr>
                                                    @else

                                                        <tr>
                                                            <td>Daily Rate</td>
                                                            <td class="text-right"><strong>Php {{ number_format($unit->short_term_daily_rate, 2) }}</strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Weekly Rate</td>
                                                            <td class="text-right"><strong>Php {{ number_format($unit->short_term_weekly_rate, 2) }}</strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Monthly Rate</td>
                                                            <td class="text-right"><strong>Php {{ number_format($unit->short_term_monthly_rate, 2) }}</strong></td>
                                                        </tr>
                                                    @endif
                                                </tr>
                                            </table>
                                        </p>
                                    </div>
                                    </div>
                                </div>
                                @endif
                            @endif
                   
                        
                        @endforeach
                        </div>
                    @empty
                        <div class="col-md-12">
                        <div class="bs-callout bs-callout-danger" >
                            No results matched your search
                        </div>
                        </div>
                    @endforelse
                </div>

            </div>
            @if(count($units) > 0 && !$byFav)
                <!--{{ $units->render() }}-->
            @endif
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>
    <section id="feat_propty" class="sugg hidden">
        <div class="container">
            <div class="row">
<!--                <div class="titl_sec">
                    <div class="col-xs-6">
                        <h3 class="main_titl text-left">SUGGESTIONS</h3>
                    </div>-->
                    <div class="clearfix"></div>
                </div>
                <div class="suggestions-section">
                    @if($sug_units)
                    @foreach($sug_units->chunk(3) AS $row)
                        <div class="row">
                        @foreach($row AS $unit)
                            @if($unit->isAvailable())
                                <div class="col-md-4 rental-feed hidden">
                                    <div class="panel panel-default unit-item suggested" data-lat="{{ $unit->property->latitude }}" data-lng="{{ $unit->property->longitude }}" data-address="{{ $unit->property->address }}">
                                    <div class="panel-image tile" style="background-image:url(@if(isset($unit->property->photos['primary'])) {{ asset("storage/{$unit->property->photos['primary'][0]}") }}@endif);background-size: cover;background-repeat: no-repeat;background-position: center;">
                                        <img style="display:none;" class="img-responsive img-hover" src="@if(isset($unit->property->photos['primary'])) {{ asset("storage/{$unit->property->photos['primary'][0]}") }}@endif" alt="">
                                        <div class="img_hov_eff">
                                            <a class="btn btn-default btn_trans" href="{{ route('view-unit', ['unit' => $unit->id]) }}"> More Details </a>
                                        </div>
                                        <div class="sal_labl">
                                            Vacant
                                        </div>
                                    </div>

                                    <div class="rental-home-feed panel-body">
                                        <div class="prop_feat">
                                            <p class="area"><i class="fa fa-home"></i> {{  $unit->property->getTypeDescription() }}</p>
                                            <p class="bedrom"><i class="fa fa-bed"></i> {{ $unit->bedrooms }} Bed(s)</p>
                                            <p class="bedrom"><i class="fa fa-bath"></i> {{ $unit->bathrooms }} Bath(s)</p>
                                        </div>
                                        <h3 class="sec_titl">{{ $unit->property->building_name }}</h3>

                                        <p class="sec_desc">
                                            {{ $unit->property->address }}
                                            <br>
                                            <span class="nearby-the-property"> Landmark: {{ $unit->property->landmarks }} </span>
                                            <table class="table table-condensed">
                                                <tr>
                                                    <td>Rental Term</td>
                                                    <td class="text-right"><strong>{{ $unit->rental_terms === 'LONG' ? 'Long Term' :  'Short Term' }}</strong></td>
                                                    @if($unit->rental_terms === 'LONG')
                                                        <tr>
                                                            <td>Rate</td>
                                                            <td class="text-right"><strong>Php {{ number_format($unit->long_term_rate, 2) }}</strong></td>
                                                        </tr>
                                                    @else

                                                        <tr>
                                                            <td>Daily Rate</td>
                                                            <td class="text-right"><strong>Php {{ number_format($unit->short_term_daily_rate, 2) }}</strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Weekly Rate</td>
                                                            <td class="text-right"><strong>Php {{ number_format($unit->short_term_weekly_rate, 2) }}</strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Monthly Rate</td>
                                                            <td class="text-right"><strong>Php {{ number_format($unit->short_term_monthly_rate, 2) }}</strong></td>
                                                        </tr>
                                                    @endif
                                                </tr>
                                            </table>
                                        </p>
                                    </div>
                                    </div>
                                </div>
                                @endif
                        @endforeach
                        </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
        <!-- /.container -->
    </section>
@endsection

@push('scripts')
    <!-- GMaps JavaScript -->
<!-- <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyC1MUjOwwLeP2Jv5Q8o0nt5RH-oSKY5RUw"></script> -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/lodash/4.17.4/lodash.min.js"></script>
<script src="{{ asset('js/custom_map.js') }}"></script>
<script src="{{ asset('js/infobox.js') }}"></script>
<script src="{{ asset('js/markerwithlabel_packed.js') }}"></script>
    
<script 
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCueneuLIZWACMGBDBQaYix4vE9X1UkP_0&callback=initialize&region=ph&libraries=geometry">
    </script>
    
    
    <script>
        $( function() {
    $( "#slider" ).slider({
      value: {{ request()->input('search_radius', 500) }},
      min: 500,
      max: 5000,
      step: 50,
      slide: function( event, ui ) {
        $( "#amount" ).val( ui.value + ' meters');
        $('[name=search_radius]').val(ui.value)
      }
    });
    $( "#amount" ).val( $( "#slider" ).slider( "value" ) + ' meters');
  } );
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush