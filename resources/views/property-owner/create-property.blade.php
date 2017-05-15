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
                            <div id="map_canvas" style="height:300px;margin-bottom:10px" ></div>
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
                                <form name="sentMessage" id="contactForm" novalidate="" class="ajax" action="{{ url('/properties') }}" method="POST" data-next="{{ url('/properties') }}">
                                    {{ csrf_field() }}
                                    <div class="bs-callout bs-callout-danger hidden" ></div>
                                    <input type="hidden" name="latitude" id="latitude">
                                    <input type="hidden" name="longitude" id="longitude">
                                    <input type="hidden" name="address">
                                    <div class="control-group form-group">
                                        <div class="controls col-md-6 first">
                                            <label>Address*</label>
                                            <p class="form-control-static" id="address"></p>
                                        </div>
                                        <div class="controls col-md-6 ">
                                            <input type="text" class="form-control" name="extension" placeholder="Subdivision, village or compound...">
                                        </div>  
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="control-group form-group">
                                        <div class="controls col-md-6 first">
                                            <label>Name of Building* </label>
                                            <input type="text" class="form-control" name="building_name">
                                        </div>
                                        <div class="controls col-md-6">
                                            <label>Permit Number* </label>
                                            <input type="text" class="form-control" name="permit_number">
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="control-group form-group">
                                        <div class="controls col-md-6 first">
                                            <label>Property Type* </label>
                                           <select class="form-control" name="property_type" id="property_type">
                                               <option value="" disabled selected></option>
                                                <option value="APARTMENT">Apartment</option>
                                                <option value="BOARDING_HOUSE">Boarding House</option>
                                                <option value="DORMITORY">Dormitory</option>
                                            </select>
                                        </div>
                                        <div class="controls col-md-6">
                                            <label>Landmarks</label>
                                            <div class="row">
                                                <div id="keywordField" style="display:none;"> <input id="keyword" type="text" /> </div>
                                                <div id="controls" class="col-sm-6 col-xs-12" style="padding:0;"> 
                                                    <span id="typeLabel" style="display:none;"><label>Type:</label> </span>
                                                    <select id="type" class="form-control">
                                                        <option value="All" selected="selected">Type</option>
                                                        <option value="bar">Bars</option>
                                                        <option value="cafe">Cafe</option>
                                                        <option value="clothing_store">Clothing store</option>
                                                        <option value="museum">Museums</option>
                                                        <option value="restaurant">Restaurants</option>
                                                        <option value="university">University</option>
                                                        <option value="church">Church</option>
                                                        <option value="police">Police Station</option>
                                                        <option value="shopping_mall">Shopping Mall</option>

                                                    </select> 
                                                    <div style="display:none;">
                                                        <span id="rankByLabel"><label>Rank by:</label></span>
                                                        <select id="rankBy" class="form-control">
                                                            <option value="prominence">Prominence</option>
                                                            <option value="distance" selected="selected">Distance</option>
                                                        </select>
                                                    </div>
                                                    
                                                </div>
                                                <div id="listing">
                                                    <div id="resultsTable" class="col-sm-6 col-xs-12" style="padding:0;">
                                                        <!-- <label>Nearby Places:</label> -->
                                                        <select id="results" class="form-control" name="landmarks">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <input type="text" class="form-control" > -->
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="control-group form-group" id="genders_form">
                                        <div class="controls col-md-6 first">
                                            <label>Gender</label>
                                            <select class="form-control" name="gender" id="genders">
                                                <option value="" disabled selected></option>
                                                @foreach($genders as $gender)
                                                <option value="{{$gender}}">{{$gender}}</option>
                                                @endforeach
                                            </select>
                                        </div>       
                                        <div class="controls col-md-6">
                                            <label>Capacity</label>
                                            <input type="text" class="form-control" name="capacity" id="capacities">
                                        </div>             
                                    </div>
                                    <div class="row">
                                        <div class="controls col-md-12">
                                            <label>Policy</label>
                                            <textarea rows="3" name="policy" class="form-control"></textarea>
                                        </div>   
                                    </div>
                                    <div class="row"></div>
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
                                    <button type="submit" class="btn btn-primary">Post</button>
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
<!-- <script src="http://maps.google.com/maps/api/js?sensor=false&libraries=places,geometry"></script> 
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_rNbVaQpg8dYpqlNMyWs3G3dMqgQwnPc&callback=initMap" type="text/javascript"></script>

-->
     
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCHfHTt-Y8HqG9rC3i5SMGjCYJyjAlQN08&libraries=places,geometry"></script>

  <!-- <script async defer 
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1MUjOwwLeP2Jv5Q8o0nt5RH-oSKY5RUw&region=PH&callback=initMap">
    </script> -->

    <script src="{{ asset('js/distance_mtrx.js') }}"></script>

    <!-- Script to Activate the Carousel -->
    <script>
        $("#genders_form").hide();
        $('#property_type').change(function() {
           if($('#property_type').val() != 'DORMITORY') {
                $("#genders").prop("selectedIndex", 0);
                $("#genders").attr("disabled", "disabled");
                $("#genders_form").hide();
                $("#capacities").val = '';
                $("#capacities").hide();
           } else {
                $("#genders_form").show();
                $("#genders").removeAttr("disabled");
                $("#capacities").show();
                $("#capacities").removeAttr("disabled");
           }
        });

    function initialize() {
        var latval = $('input[name="latitude"]').val();
        var langval = $('input[name="longitude"]').val();

        //alert(langval);

        var cebuCity = {lat: 10.31552939472010, lng: 123.88557580947874};
        myLatlng = new google.maps.LatLng(cebuCity);
        var myOptions = {
            zoom: 15,
            center: cebuCity,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
        places = new google.maps.places.PlacesService(map);
        google.maps.event.addListener(map, 'tilesloaded', tilesLoaded);

        

        geocoder  = new google.maps.Geocoder;
        infowindow = new google.maps.InfoWindow;

        map.addListener('click', function(e){
            var center = e.latLng;
            marker.setPosition(center);
            getAddress(center, function(result){
                setMapValues(result.formatted_address, center.lat(), center.lng())
            });
            // function pan() {
                //myCenter = new google.maps.LatLng(document.getElementById("latitude").value, document.getElementById("longitude").value);
                map.panTo(center);
                $('#type').val('All').trigger('change');
            //alert(myCenter);

            // }
            //alert(center);
        });

        marker = new google.maps.Marker({
            map: map, position: map.getCenter(), animation: google.maps.Animation.DROP,
            icon: 'images/marker.png'
        });

        $('#search-address').keyup(function(e){
            if(e.keyCode === 13){
                searchAddress($(this).val(), function(result){
                    marker.setPosition(result.geometry.location);
                    map.setCenter(result.geometry.location);
                    setMapValues(result.formatted_address, result.geometry.location.lat(), result.geometry.location.lng())
                });
            }
        });


        document.getElementById('keyword').onkeyup = function (e) {
            if (!e) var e = window.event;
            if (e.keyCode != 13) return;
            document.getElementById('keyword').blur();
            search(document.getElementById('keyword').value);
        }

        var typeSelect = document.getElementById('type');
        typeSelect.onchange = function () {
            search();
        };

        var rankBySelect = document.getElementById('rankBy');
        rankBySelect.onchange = function () {
            ssssearch();
        };

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

         initialize();
        /* Product Slider Codes */
        $(document).ready(function () {
            'use strict';
        });
            
    </script>
@endpush