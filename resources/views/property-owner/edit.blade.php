@extends('layouts.app')

@section('content')
    @component('components.breadcrumbs')
       <p class="lnk_pag"><a> add property </a> </p>
    @endcomponent

    <div class="spacer-60"></div>
    <div class="container">
        <div class="row">
            <!-- Proerty Details Section -->
            <section id="prop_detal" class="col-md-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="titl_sec">
                            <h3 class="main_titl text-left">Property Map</h3>
                            
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="prop_map" style="margin:0;padding-bottom:10px;">
                            <div id="map" style="height:300px;margin-bottom:10px" ></div>
                            <input type="text" class="form-control" placeholder="Search for an address" id="search-address">
                        </div>
                       
                    </div>
                </div>
                <div class="spacer-30"></div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="titl_sec">
                            <h3 class="main_titl text-left">Property details</h3>
                            <span class="this-is-required">* is required</span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-body" style="padding-top:25px">
                                <form name="sentMessage" action="{{ url('/properties/'.$property->id) }}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="bs-callout bs-callout-danger hidden" ></div>
                                    <input type="hidden" name="latitude" value="{{$property->latitude}}">
                                    <input type="hidden" name="longitude" value="{{$property->longitude}}">
                                    <input type="hidden" name="address" value="{{$property->address}}">
                                    <input type="hidden" name="id" value="{{$property->id}}" />
                                    <input type="hidden" name="update" value="true">
                                    <div class="control-group form-group">
                                        <div class="controls col-md-6 first">
                                            <label>Address*</label>
                                            <p class="form-control-static" id="address">{{$property->address}}</p>
                                        </div>
                                        <div class="controls col-md-6 ">
                                            <input type="text" class="form-control" name="extension" placeholder="Subdivision, village or compound..." value="{{$property->extension}}">
                                        </div>  
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="control-group form-group">
                                        <div class="controls col-md-6 first">
                                            <label>Name of Building* </label>
                                            <input type="text" class="form-control" name="building_name" value="{{$property->building_name}}">
                                        </div>
                                        <div class="controls col-md-6">
                                            <label>Permit Number* </label>
                                            <input type="text" class="form-control" name="permit_number" value="{{$property->permit_number}}">
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                     <div class="control-group form-group">
                                        <div class="controls col-md-6 first">
                                            <label>Property Type* </label>
                                           <select class="form-control" name="property_type" id="property_type">
                                               <option value="" @if($property->property_type == '') disabled selected @endif></option>
                                                <option value="APARTMENT" @if($property->property_type == 'APARTMENT') selected @endif>Apartment</option>
                                                <option value="BOARDING_HOUSE" @if($property->property_type == 'BOARDING_HOUSE') selected @endif>Boarding House</option>
                                                <option value="DORMITORY" @if($property->property_type == 'DORMITORY') selected @endif>Dormitory</option>
                                            </select>
                                        </div>
                                        <div class="controls col-md-6">
                                            <label>Landmarks</label>
                                            <input type="text" class="form-control" name="landmarks" value="{{$property->landmarks}}">
                                        </div>

                                        <div class="control-group form-group" id="genders_form">
                                        <div class="controls col-md-6 first">
                                            <label>Gender</label>
                                            <select class="form-control" name="gender" id="genders">
                                                <option value="" disabled selected></option>
                                                @foreach($genders as $gender)
                                                <option value="{{$gender}}" @if ($gender == $property->gender) selected @endif>{{$gender}}</option>
                                                @endforeach
                                            </select>
                                        </div>       
                                        <div class="controls col-md-6">
                                            <label>Capacity</label>
                                            <input type="text" class="form-control" name="capacity" id="capacities" value="{{$property->capacity}}">
                                        </div>             
                                    </div>
                                    <div class="row"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="row" style="margin-top:20px">
                                        <div class="col-sm-4">
                                            <div class="prop_addinfo">
                                                <h2 class="add_titl">
                                                    Primary Photo*
                                                </h2>
                                                <!-- <div class="alert alert-success">* Required</div> -->
                                                <div class="info_sec first">
                                                    <div class="form-group">
                                                        <label>Photo # 1</label>
                                                        <input type="file" name="photos[primary][0]">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Photo # 2</label>
                                                        <input type="file" name="photos[primary][1]">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="prop_addinfo">
                                                <h2 class="add_titl">
                                                    Interior Photo
                                                </h2>
                                                <div class="info_sec first">
                                                    <div class="form-group">
                                                        <label>Photo # 1</label>
                                                        <input type="file" name="photos[interior][0]">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Photo # 2</label>
                                                        <input type="file" name="photos[interior][1]">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="prop_addinfo">
                                                <h2 class="add_titl">
                                                    Bedrooms Photo
                                                </h2>
                                                <div class="info_sec first">
                                                    <div class="form-group">
                                                        <label>Photo # 1</label>
                                                        <input type="file" name="photos[bedrooms][0]">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Photo # 2</label>
                                                        <input type="file" name="photos[bedrooms][1]">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top:20px">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <div class="prop_addinfo">
                                                <h2 class="add_titl">
                                                    Bathrooms Photo
                                                </h2>
                                                <div class="info_sec first">
                                                    <div class="form-group">
                                                        <label>Photo # 1</label>
                                                        <input type="file" name="photos[bathrooms][0]">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Photo # 2</label>
                                                        <input type="file" name="photos[bathrooms][1]">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="prop_addinfo">
                                                <h2 class="add_titl">
                                                    Amenities Photo
                                                </h2>
                                                <div class="info_sec first">
                                                    <div class="form-group">
                                                        <label>Photo # 1</label>
                                                        <input type="file" name="photos[amenities][0]">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Photo # 2</label>
                                                        <input type="file" name="photos[amenities][1]">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- For success/fail messages -->
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="spacer-30"></div>

            </section>

            <!-- Sidebar Section -->       

        </div>
    </div>
@endsection

@push('scripts')

    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1MUjOwwLeP2Jv5Q8o0nt5RH-oSKY5RUw&region=PH&callback=initMap">
    </script>

    <!-- Script to Activate the Carousel -->
    <script>

        $('#property_type').change(function() {
           if($('#property_type').val() != 'DORMITORY') {
                $("#genders_form").hide();
                $("#capacities").val = '';
                $("#capacities").hide();
           } else {
                $("#genders_form").show();
                $("#capacities").show();
                $("#capacities").removeAttr("disabled");
           }
        }).trigger('change');

        var map,
            geocoder,
            infowindow,
            marker;

        function initMap(){
             var cebuCity = {lat: 10.31552939472010, lng: 123.88557580947874};
            
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: cebuCity
            });
                
            marker = new google.maps.Marker({
                map: map,
                position: map.getCenter(),
                animation: google.maps.Animation.DROP
            });

            geocoder  = new google.maps.Geocoder;
            infowindow = new google.maps.InfoWindow;

            map.addListener('click', function(e){
                var center = e.latLng;
                marker.setPosition(center);
                // map.setCenter(center);
                getAddress(center, function(result){
                    setMapValues(result.formatted_address, center.lat(), center.lng())
                });
            })

            $('#search-address').keyup(function(e){
                if(e.keyCode === 13){
                    searchAddress($(this).val(), function(result){
                        marker.setPosition(result.geometry.location);
                        map.setCenter(result.geometry.location)
                        // console.log(result);
                        setMapValues(result.formatted_address, result.geometry.location.lat(), result.geometry.location.lng())
                    })
                }
            })
        }

        function getAddress(latlng, callback){
            geocoder.geocode({'location': latlng}, function(results, status) {
                if (status === 'OK') {
                    if (results[0]) {
                        console.log(results[0]);
                        callback(results[0])
                    }
                } else {
                    console.log(status);
                    // window.alert('Geocoder failed due to: ' + status);
                }
            });
        }

        function searchAddress(val, callback){
            geocoder.geocode({'address': val}, function(results, status) {
                if (status === 'OK') {
                    console.log(results[0]);
                    callback(results[0])
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        }

        function setMapValues(address, lat, lng){
            $('#address').text(address)
            $('[name=address]').val(address)
            $('[name=latitude]').val(lat)
            $('[name=longitude]').val(lng)
        }

        /* Product Slider Codes */
        $(document).ready(function () {
            'use strict';
        });
            
    </script>
@endpush