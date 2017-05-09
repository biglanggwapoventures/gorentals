function initialize() {
    google.maps.Circle.prototype.contains = function(latLng) {
        return this.getBounds().contains(latLng) && google.maps.geometry.spherical.computeDistanceBetween(this.getCenter(), latLng) <= this.getRadius();
    }
    var positionCircle = new google.maps.Circle({
        strokeColor: '#FF0000',
        strokeOpacity: 0.8, 
        strokeWeight: 2,
        fillColor: '#FF0000',
        fillOpacity: 0.35,
        // map: map,
        // center: pos,
        radius: parseInt($('[name=search_radius]').val())
    });;
    //default localtion
    var cebuCity = new google.maps.LatLng(10.3541, 123.9116), // Main Map Latitude and Longitude
    markers,
    myMapOptions = {zoom: 5, center: cebuCity, mapTypeId: google.maps.MapTypeId.ROADMAP };
    // map = new google.maps.Map(document.getElementById("map"), myMapOptions);

    var map = new google.maps.Map(document.getElementById("map"), {scaleControl: false,zoom: 15, mapTypeId: google.maps.MapTypeId.ROADMAP });
    //var map = map;
    var infoWindow = new google.maps.InfoWindow({map: map });


 // Try HTML5 geolocation.
//  if (navigator.geolocation) {
//    navigator.geolocation.getCurrentPosition(function(position) {
       var pos = {};
    if($("[name=search_lat]").val() && $('[name=search_lng]').val()){
      
         pos.lat = parseFloat($("[name=search_lat]").val())
         pos.lng =  parseFloat($("[name=search_lng]").val())
     
    }else{

        //  pos.lat = position.coords.latitude,
        // pos.lng =  position.coords.longitude
        pos.lat = 10.3541;
        pos.lng =  123.9116;
    }
    
     infoWindow.setPosition(pos);
     infoWindow.setContent('<b>You are here.</b>');
     map.setCenter(pos);
     var marker = new google.maps.Marker({
       position: pos,
       map: map,
       title: String(pos.lat) + ", " + String(pos.lng),
     });
     positionCircle.setCenter(pos);
     positionCircle.setMap(map);
     google.maps.event.addListener(positionCircle, "click", function(e){
         console.log(e)
        // alert("Circle clicked");
        google.maps.event.trigger(map, 'click', e);
    });

      var _markers = [];
       var resultItems = [],
            suggestions = [];

    $('.unit-item').each(function(){
        var result = $(this).hasClass('result'),
            data = $(this).data(),  
            ltLng = new google.maps.LatLng(data.lat, data.lng);

        var marker = {
            loc: data.address, 
            latLng: ltLng,
            propImg: $(this).find('.img-hover').attr('src'), 
            propTitle: $(this).find('.sec_titl').text(),
            propNearby: $(this).find('.nearby-the-property').text(),
            propLink: $(this).find('.img_hov_eff a').attr('href'),
            markLabl: 'images/apartment.png',
        };


       
        // console.log
        if(result){
            // console.log(propTitle);
            if(positionCircle.contains(ltLng)){
                _markers.push(marker);
                resultItems.push($(this).clone());
            }else{
                suggestions.push($(this).clone());
            }
        }else{
            // consolel.log($(this));
             if(positionCircle.contains(ltLng) && parseInt($('#feat_propty').data('permit'))){
                 _markers.push(marker);
                 resultItems.push($(this).clone());
             }else{
                 suggestions.push($(this).clone());
             }
        }

       


        
    }); 

     if(resultItems.length){
        $('.results-section').html('');
        var chunks = _.chunk(resultItems, 3);
        var html = '<div class="row">';
        $(chunks).each(function(i, chunk){
            $(chunk).each(function(i, v){
                console.log(v);
                html += ('<div class="col-md-4">' + $(v[0]).prop('outerHTML') + '</div>');
            })
        })
        html += '</div>'
        $('.results-section').html(html);
    }else{
        $('.sugg').removeClass('hidden');
        $('.suggestions-section').html('');
        var chunks = _.chunk(suggestions, 3);
        var html = '<div class="row">';
        $(chunks).each(function(i, chunk){
            $(chunk).each(function(i, v){
                console.log(v);
                html += ('<div class="col-md-4">' + $(v[0]).removeClass('hidden').prop('outerHTML') + '</div>');
            })
        })
        html += '</div>'
        $('.suggestions-section').html(html);
    }

    $('[name=address]').change(function () {
        var val = $(this).val();
        if(!val) return;
        getBarangayCoords(val, marker);
    })


    //
    function getBarangayCoords(barangay, marker) {
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({"address":barangay+' cebu city' }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                console.log(results[0])
                var lat = results[0].geometry.location.lat(),
                    lng = results[0].geometry.location.lng(),
                    placeName = results[0].address_components[0].long_name,
                    latlng = new google.maps.LatLng(lat, lng);
                    marker.setPosition(latlng);
                    map.setCenter(latlng);
                    if(positionCircle){
                        positionCircle.setCenter(latlng);
                    }
                    $('[name=search_lat]').val(lat)
                    $('[name=search_lng]').val(lng)
            }
        });   
     }

    map.addListener('click', function (e) {
        var latlng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
        marker.setPosition(latlng);
        map.setCenter(latlng);
        if(positionCircle){
            positionCircle.setCenter(latlng);
        }
        $('[name=search_lat]').val(e.latLng.lat())
        $('[name=search_lng]').val(e.latLng.lng())
        
    });

    //here the call to initMarkers() is made with the necessary data for each marker.  All markers are then returned as an array into the markers variable
    markers = initMarkers(map, _markers);
//    }, function() {
//      handleLocationError(true, infoWindow, map.getCenter());
//    });
//  } else {
//    // Browser doesn't support Geolocation
//    handleLocationError(false, infoWindow, map.getCenter());
//  }

    // display zoom control start
    var zoomControlDiv = document.createElement('div');
    var zoomControl = new ZoomControl(zoomControlDiv, map);

    zoomControlDiv.index = 1;
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(zoomControlDiv);
    // display zoom control end

    //search input start
    marker = new google.maps.Marker({
            position: cebuCity,
            map: map,
            icon: 'image/marker.png'
         });
    var input = document.getElementById('searchTextField');         
    // var autocomplete = new google.maps.places.Autocomplete(input, {
    //     types: ["geocode"]
    // });          
    
    // autocomplete.bindTo('bounds', map); 
    var infowindow = new google.maps.InfoWindow(); 
 
    // google.maps.event.addListener(autocomplete, 'place_changed', function() {
    //     infowindow.close();
    //     var place = autocomplete.getPlace();
    //     if (place.geometry.viewport) {
    //         map.fitBounds(place.geometry.viewport);
    //     } else {
    //         map.setCenter(place.geometry.location);
    //         map.setZoom(17);  
    //     }
        
    //     moveMarker(place.name, place.geometry.location);
    // });  
    
    $("input#searchTextField").focusin(function () {
        $(document).keypress(function (e) {
            if (e.which == 13) {
                 selectFirstResult();   
            }
        });
    });
    $("input#searchTextField").focusout(function () {
        if(!$(".pac-container").is(":focus") && !$(".pac-container").is(":visible"))
            selectFirstResult();
    });

    function selectFirstResult() {
        infowindow.close();
        $(".pac-container").hide();
        var firstResult = $(".pac-container .pac-item:first").text();
        
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({"address":firstResult }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                var lat = results[0].geometry.location.lat(),
                    lng = results[0].geometry.location.lng(),
                    placeName = results[0].address_components[0].long_name,
                    latlng = new google.maps.LatLng(lat, lng);
                
                moveMarker(placeName, latlng);
                $("input").val(firstResult);
            }
        });   
     }

     


     function moveMarker(placeName, latlng){
        marker.setIcon(image);
        marker.setPosition(latlng);
        infowindow.setContent(placeName);
        infowindow.open(map, marker);
     }
    //search input end 

    // custom zoom start
    function ZoomControl(controlDiv, map) {
        // Creating divs & styles for custom zoom control
        controlDiv.style.padding = '5px';

        // Set CSS for the control wrapper
        var controlWrapper = document.createElement('div');
        controlWrapper.style.backgroundColor = 'white';
        controlWrapper.style.borderStyle = 'solid';
        controlWrapper.style.borderRadius = '3px';
        controlWrapper.style.borderColor = 'gray';
        controlWrapper.style.borderWidth = '1px';
        controlWrapper.style.cursor = 'pointer';
        controlWrapper.style.textAlign = 'center';
        controlWrapper.style.width = '32px'; 
        controlWrapper.style.height = '64px';
        controlDiv.appendChild(controlWrapper);

        // Set CSS for the zoomIn
        var zoomInButton = document.createElement('div');
        zoomInButton.style.width = '32px'; 
        zoomInButton.style.height = '32px';
        /* Change this to be the .png image you want to use */
        zoomInButton.style.backgroundImage = 'url("images/plus-xxl.png")';
        zoomInButton.style.backgroundSize = '14px';
        zoomInButton.style.backgroundRepeat = 'no-repeat';
        zoomInButton.style.backgroundPosition = 'center center';
        controlWrapper.appendChild(zoomInButton);

        // Set CSS for the zoomOut
        var zoomOutButton = document.createElement('div');
        zoomOutButton.style.width = '32px'; 
        zoomOutButton.style.height = '32px';
        /* Change this to be the .png image you want to use */
        zoomOutButton.style.backgroundImage = 'url("images/minus-7-xxl.png")';
        zoomOutButton.style.backgroundSize = '14px';
        zoomOutButton.style.backgroundRepeat = 'no-repeat';
        zoomOutButton.style.backgroundPosition = 'center center';
        controlWrapper.appendChild(zoomOutButton);

        // Setup the click event listener - zoomIn
        google.maps.event.addDomListener(zoomInButton, 'click', function() {
        map.setZoom(map.getZoom() + 1);
        });

        // Setup the click event listener - zoomOut
        google.maps.event.addDomListener(zoomOutButton, 'click', function() {
        map.setZoom(map.getZoom() - 1);
        });  
    } // custom zoom control end

    function getLatitudeLongitude(callback, address) {
        // If adress is not supplied, use default value 'Ferrol, Galicia, Spain'
        address = searchTextField || 'Ferrol, Galicia, Spain';
        // Initialize the Geocoder
        geocoder = new google.maps.Geocoder();
        if (geocoder) {
            geocoder.geocode({
                'searchTextField': address
            }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    callback(results[0]);
                }
            });
        }
    }

    // map markers start
    function initMarkers(map, markerData) {
        var newMarkers = [], marker;
        // for loop start
        for (var i = 0; i < markerData.length; i++) {
            var pictureLabel = document.createElement("img");
            pictureLabel.src = markerData[i].markLabl;
            var boxText = document.createElement("div");
            // infoboxes start
            var infoboxOptions = {
                content: boxText, disableAutoPan: false, maxWidth: 0,
                pixelOffset: new google.maps.Size(-180, -360),
                zIndex: null, boxStyle: { opacity: 1, width: "auto" },
                closeBoxMargin: "0", closeBoxURL: "images/close_btn.jpg",
                infoBoxClearance: new google.maps.Size(1, 1),
                isHidden: false, pane: "floatPane", enableEventPropagation: true
            };
            // infoboxes end

            // marker start
            marker = new MarkerWithLabel({
                title: markerData[i].loc, 
                strokeColor: '#FF0000', 
                strokeOpacity: 0.8,
                strokeWeight: 2, 
                fillColor: '#FF0000',
                fillOpacity: 0.35, 
                // radius: milesToMeters(.2),
                map: map, draggable: false, icon: 'images/marker.png',
                // geofence
                center: markerData[i].latLng, 
                //marker
                position: markerData[i].latLng,
                visible: true, labelContent: pictureLabel, labelAnchor: new google.maps.Point(0, 0),
            });
            //marker end

            // circle = new google.maps.Circle(marker);
            // console.log(marker);
            // function milesToMeters(miles){
            //     return miles * 1609.34;
            // }

            newMarkers.push(marker);
            //define the text and style for all infoboxes
            boxText.style.cssText = "";
            boxText.innerHTML = '<div class="col-xs-12 p0" style="width:350px"><div class="panel panel-default"><div class="panel-image" style="overflow:hidden"><img class="img-responsive img-hover" src="' + markerData[i].propImg + '" alt=""></div><div class="panel-body"><h3 class="sec_titl"><a href="'+ markerData[i].propLink +'">' + markerData[i].propTitle + '</a> </h3><div class=""><p class="sec_titl"> ' + markerData[i].propNearby + '</p></div></div></div></div>';
            //Define the infobox
            newMarkers[i].infobox = new InfoBox(infoboxOptions);
            //Open box when page is loaded
            newMarkers[i].infobox.close(map, marker);
            //Add event listen, so infobox for marker is opened when user clicks on it.  Notice the return inside the anonymous function - this creates
            //a closure, thereby saving the state of the loop variable i for the new marker.  If we did not return the value from the inner function, 
            //the variable i in the anonymous function would always refer to the last i used, i.e., the last infobox. This pattern (or something that
            //serves the same purpose) is often needed when setting function callbacks inside a for-loop.
            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    var h;
                    for (h = 0; h < newMarkers.length; h++) {
                        newMarkers[h].infobox.close();
                    }
                    newMarkers[i].infobox.open(map, this);
                    map.panTo(markerData[i].latLng);
                }
            })(marker, i));
        } // for loop end
        return newMarkers;
    } // map markers end

   
}
// initialize();
