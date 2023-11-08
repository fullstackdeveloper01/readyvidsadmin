@extends('layouts.app', ['title' => __('City Management')])



@section('content')

    @include('city.partials.header', ['title' => __('Add City')])

    <div class="container-fluid mt--7">

        <div class="row">

            <div class="col-xl-12 order-xl-1">

                <div class="card bg-secondary shadow">

                    <div class="card-header bg-white border-0">

                        <div class="row align-items-center">

                            <div class="col-8">

                                <!--<h3 class="mb-0">{{ __('City Management') }}</h3>-->

                            </div>

                            <div class="col-4 text-right">

                                <a href="{{ route('city.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>

                            </div>

                        </div>

                    </div>



                    <div class="card-body">

                        <!--<h6 class="heading-small text-muted mb-4">{{ __('City information') }}</h6>-->

                        <div class="pl-lg-4">

                            <form method="post" action="{{ route('city.store') }}" autocomplete="off">

                                @csrf
                                

                                </div>

                                <div class="pl-lg-4">
                                    <input type="hidden" id="city" name="city_name">
                                    <input type="hidden" id="place_id" name="place_id">
                                    <input type="hidden" id="late" name="late">
                                    <input type="hidden" id="long" name="long">

                                    <div class="form-group{{ $errors->has('city_name') ? ' has-danger' : '' }}">

                                        <label class="form-control-label" for="city_name">{{ __('City Name') }}</label>

                                        <input type="text" name="" id="city_name" class="form-control form-control-alternative{{ $errors->has('city_name') ? ' is-invalid' : '' }}" placeholder="{{ __('City Name') }}" value="" required>
                                        <span id="city_error" style="color:red;"></span>
                                        @if ($errors->has('city_name'))

                                            <span class="invalid-feedback" role="alert">

                                                <strong>{{ $errors->first('city_name') }}</strong>

                                            </span>

                                        @endif

                                    </div>
                                    <div id="map"></div>

                                    <div class="text-center">

                                        <button type="submit" class="btn btn-success btn-sm mt-4">{{ __('Save') }}</button>

                                    </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>
       
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDFbqXYRw_p-4uzP4M2PK38qvR8vc_wav4&region=GB&libraries=places&region=us&callback=initialize " async defer></script> -->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAPFhEOvXyYOFWxKwtZ5H8ZlGLOnPEJqiM&region=GB&libraries=places&region=us&callback=initialize " async defer></script>
        <script type="text/javascript">
            function initialize() {
              var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: -33.8688, lng: 151.2195},
                zoom: 13,
              });

              // Create the search box and link it to the UI element.
              var input = document.getElementById('city_name');
              // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
              const options = {
                  // bounds: defaultBounds,
                  // componentRestrictions: { country: "us" },
                  // fields: ["address_components", "geometry", "icon", "name"],
                  // strictBounds: false,
                  // types: ["establishment"],
              };
              var autocomplete = new google.maps.places.Autocomplete(input,options);
              autocomplete.bindTo('bounds', map);

              var infowindow = new google.maps.InfoWindow();
              var marker = new google.maps.Marker({
                    map: map,
                    anchorPoint: new google.maps.Point(0, -29)
              });

              autocomplete.addListener('place_changed',function(){
                infowindow.close();
                marker.setVisible(false);

                var place = autocomplete.getPlace();
                console.log('response',place);
                if (!place.geometry) {
                    window.alert("Autocomplete's returned place contains no geometry");
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17); // Why 17? Because it looks good.
                }

                marker.setIcon( /** @type {google.maps.Icon} */ ({
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(25, 34),
                    scaledSize: new google.maps.Size(50, 50)
                }));

                marker.setPosition(place.geometry.location);
                marker.setVisible(true);

                var address = '';

                if (place.address_components) {
                    address = [
                        (place.address_components[0] && place.address_components[0].short_name || ''),
                        (place.address_components[1] && place.address_components[1].short_name || ''),
                        (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
                }

                infowindow.setContent('<div id="infowindow"><strong>' + place.name + '</strong><br>' + 'Place ID: ' + place.place_id + '<br>' + place.formatted_address);
                infowindow.open(map, marker);


                for (var i = 0; i < place.address_components.length; i++) {
                    // if (place.address_components[i].types[0] == 'postal_code') {
                    //     document.getElementById('postal_code').innerHTML = place.address_components[i].long_name;
                    // }
                    // if (place.address_components[i].types[0] == 'country') {
                    //     document.getElementById('country').innerHTML = place.address_components[i].long_name;
                    // }

                    if (place.address_components[i].types[0] == "locality") {
                        document.getElementById('city').value = place.address_components[i].long_name;
                    }
                    document.getElementById('place_id').value = place.place_id;
                    document.getElementById('late').value = place.geometry.location.lat();
                    document.getElementById('long').value = place.geometry.location.lng();
                }
              });
            }

        </script>
        

        <script type="text/javascript">

            $(document).ready(function(){

                // $.ajax({

                //     url: '/getState',

                //     type: 'get',

                //     dataType: 'json',

                //     success: function(response){

                //         if(response.data!=""){

                //             var html ='';

                //             $.each(response.data,function(key,value){

                //                 html+='<option value="'+value.id+'">'+value.state_name+'</option>'

                //             })

                //             $('#state_id').html(html);

                //         }else{

                //             $('#state_id').html('<option value="">No State Found</option>');

                //         }

                //     }

                // });

            })

        </script>

        @include('layouts.footers.auth')

    </div>

@endsection

