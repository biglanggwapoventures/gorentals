    function initialize() {
// 'use strict';
            var cebuCity = new google.maps.LatLng(10.31552939472010, 123.88557580947874), // Main Map Latitude and Longitude
            markers,
            myMapOptions = {
                    zoom: 15,
                    center: cebuCity,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                },
                map = new google.maps.Map(document.getElementById("map"), myMapOptions);

            // if (navigator.geolocation) {
            //     navigator.geolocation.getCurrentPosition(function(position) {
            //         console.log(position)
            //         var pos = {
            //             lat: position.coords.latitude,
            //             lng: position.coords.longitude
            //         };

            //         map.setCenter(pos);
            //     }, function() {
            //         alert('Error: The Geolocation service failed');
            //     });
            // } else {
            //     alert('Error: Your browser doesn\'t support geolocation.');
            // }

           

            // {
            //         loc: 'powai',
            //         latLng: new google.maps.LatLng(49.47216, -123.76307),
            //         propImg: 'images/property/property_2.jpg',
            //         propTitle: 'Amillarah Private Islands',
            //         for: 'For Saless',
            //         price: '$3200',
            //         area: '5000 Sq Ft',
            //         propInfo: '3 Bedrooms',
            //         propInfo2: '1 Garage',
            //         markLabl: 'images/apartment.png'
            //     },

          


            function initMarkers(map, markerData) {

                var newMarkers = [],
                    marker;

                for (var i = 0; i < markerData.length; i++) {
                    var pictureLabel = document.createElement("img");
                    pictureLabel.src = markerData[i].markLabl;
                    var boxText = document.createElement("div");
                    //these are the options for all infoboxes
                    var infoboxOptions = {
                        content: boxText,
                        disableAutoPan: false,
                        maxWidth: 0,
                        pixelOffset: new google.maps.Size(-180, -360),
                        zIndex: null,
                        boxStyle: {
                            opacity: 1,
                            width: "auto"
                        },
                        closeBoxMargin: "0",
                        closeBoxURL: "images/close_btn.jpg",
                        infoBoxClearance: new google.maps.Size(1, 1),
                        isHidden: false,
                        pane: "floatPane",
                        enableEventPropagation: true
                    };

                    marker = new MarkerWithLabel({
                        title: markerData[i].loc,
                        map: map,
                        draggable: false,
                        icon: 'images/marker.png',
                        position: markerData[i].latLng,
                        visible: true,
                        labelContent: pictureLabel,
                        labelAnchor: new google.maps.Point(50, 0),
                    });

                    newMarkers.push(marker);
                    //define the text and style for all infoboxes
                    boxText.style.cssText = "";
                    boxText.innerHTML = '<div class="col-xs-12 p0" style="width:350px"><div class="panel panel-default"><div class="panel-image" style="overflow:hidden"><img class="img-responsive img-hover" src="' + markerData[i].propImg + '" alt=""></div><div class="panel-body"><h3 class="sec_titl">' + markerData[i].propTitle + ' </h3><div class="col_labls"><p class="or_labl">' + markerData[i].for+'</p><p class="blu_labl"> ' + markerData[i].price + '</p></div></div></div></div>';
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
                }

                return newMarkers;
            }



             var _markers = [];
              $('.unit-item').each(function(){
                var data = $(this).data();
                _markers.push({
                    loc: data.address,
                    latLng: new google.maps.LatLng(data.lat, data.lng),
                    propImg: $(this).find('.img-hover').attr('src'),
                    propTitle: $(this).find('.sec_titl').text(),
                    markLabl: 'images/apartment.png',
                })
            })

            //here the call to initMarkers() is made with the necessary data for each marker.  All markers are then returned as an array into the markers variable
        markers = initMarkers(map, _markers);
        }

