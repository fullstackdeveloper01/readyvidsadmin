@extends('layouts.app', ['title' => __('Details')])



@section('content')

    <style>

        .btn-toggle {

            margin: 0 auto;

            padding: 0;

            position: relative;

            border: none;

            height: 1.5rem;

            width: 3rem;

            border-radius: 1.5rem;

            color: #6b7381;

            background: #bdc1c8;

        }

        .btn-toggle:focus,

        .btn-toggle.focus,

        .btn-toggle:focus.active,

        .btn-toggle.focus.active {

            outline: none;

        }

        .btn-toggle:before,

        .btn-toggle:after {

            line-height: 1.5rem;

            width: 4rem;

            text-align: center;

            font-weight: 600;

            font-size: 8px;

            text-transform: uppercase;

            letter-spacing: 2px;

            position: absolute;

            bottom: 0;

            transition: opacity 0.25s;

        }

        .btn-toggle:before {

            content: '';

            left: -5rem;

        }

        .btn-toggle:after {

            content: '';

            right: -4rem;

            opacity: 0.5;   

        }

        .btn-toggle > .handle {

            position: absolute;

            top: 0.1875rem;

            left: 0.1875rem;

            width: 1.125rem;

            height: 1.125rem;

            border-radius: 1.125rem;

            background: #fff;

            transition: left 0.25s;

        }

        .btn-toggle.active {

            transition: background-color 0.25s;

            background-color: #29b5a8;

        }

        .btn-toggle.active > .handle {

            left: 1.6875rem;

            transition: left 0.25s;

        }

        .btn-toggle.active:before {

            opacity: 0.5;

        }

        .btn-toggle.active:after {

            opacity: 1;

        }

        

    </style>

    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8 text-right">

        <div class="container-fluid">

            <a href="{{ route('girls.index') }}" class="btn btn-primary btn-md mr">Back</a>

        </div>

    </div>

    <div class="container-fluid mt--7">

        <div class="row">

            <div class="col-xl-12">

                <br />

                <div class="card bg-secondary shadow">

                    <div class="card-header bg-white border-0 bg-yellow-fade br-6 bordered">

                        <div class="row form-group">

                            <div class="col-6">

                            <small>Full Name</small>

                                <h4 class="mb-0">{{ $girl->full_name}}</h4>

                            </div>
                            <div class="col-4">

                                <small>City</small>

                                <h4 class="mb-0">{{ App\Helpers\Helper::cityName($girl->city_id) }}</h4>

                            </div>

                        </div>

                        <!-- <div class="row form-group">

                            <div class="col-4">

                                <small>Country</small>

                                <h4 class="mb-0">{{ App\Helpers\Helper::countryName($girl->country_id) }}</h4>

                            </div>                                                                 

                            <div class="col-4">

                                <small>State</small>

                                <h4 class="mb-0">{{ App\Helpers\Helper::stateName($girl->state_id) }}</h4>

                            </div>

                            <div class="col-4">

                                <small>City</small>

                                <h4 class="mb-0">{{ App\Helpers\Helper::cityName($girl->city_id) }}</h4>

                            </div>                                                                 

                        </div> -->

                        <div class="row form-group">

                            <div class="col-6">

                                <!-- <button type="button" class="btn btn-sm btn-toggle active" data-toggle="button" id="driverSettlementStatus" aria-pressed="true" autocomplete="off">

                                    <div class="handle"></div>

                                </button> -->

                            </div>                                                                 

                        </div>

                    </div>

                </div>

                <br />

                <!-- <div class="card card-profile shadow">

                    <div class="card-header">

                        <h6 class="heading-small text-muted mb-0">Contact Details</h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-lg-12">

                                <p>

                                   {{--$listing->address--}},<br> {{--$listing->map--}}

                                </p>

                            </div>

                        </div>

                    </div>

                </div> -->

                <br />

                <div class="card card-profile shadow">

                    <div class="card-header">

                        <h6 class="heading-small text-muted mb-0">Uploaded Files</h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-lg-12">

                            @php

                                $images = explode(',',$girl->image);

                            @endphp

                            @if(isset($images))

                                @foreach($images as $image)

                                    <img class="image img-responsive" width="80px" height="80px" data-toggle="modal" data-target="#imageModal" src="{{url('uploads/girls').'/'.$image}}" >

                                @endforeach

                            @endif

                                                       

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-xl-3 mb-5 mb-xl-0">

                <!-- <br />

                <div class="card card-profile shadow text-center">

                    <div class="card-header">

                    /*@//if($listing->status==0)*/

                        <button type="button" onclick="changeStatus('{{--$listing->id--}}')" class="btn btn-sm btn-toggle listingStatus" data-toggle="button" aria-pressed="true" autocomplete="off">

                            <div class="handle"></div>

                        </button>

                    /*@//else*/

                        <button type="button" onclick="changeStatus('{{--$listing->id--}}')" class="btn btn-sm btn-toggle active listingStatus" data-toggle="button" aria-pressed="true" autocomplete="off">

                            <div class="handle"></div>

                        </button>

                    /*@//endif*/

                    

                    </div>

                </div>

                <br /> -->

                <!-- <div class="card card-profile shadow">

                    <div class="row">

                        <div class="col-lg-12">

                            <div class="card-header">

                                <h6 class="heading-small text-muted mb-0">Services</h6>

                            </div>

                            <div class="card-body">

                                <div class="">

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <p><span class="btn badge badge-success badge-pill">{{--$listing->service--}}</span></p>

                                        </div>                                            

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <br /> -->

                <!-- <div class="card card-profile shadow">

                    <div class="row">

                        <div class="col-lg-12">

                            <div class="card-header">

                                <h6 class="heading-small text-muted mb-0">Map</h6>

                            </div>

                            <div class="card-body">

                                <div class="d-flex align-items-center">

                                    {{--$listing->map--}}

                                </div>

                            </div>

                        </div>

                    </div>

                </div> -->

            </div>

        </div>

        <footer class="footer">

            <div class="row align-items-center justify-content-xl-between">                   

            </div>

        </footer>

        <div class="modal fade modal-xl" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">

            <div class="modal-dialog modal-l modal-dialog-centered" role="document">

                <div class="modal-content">

                    <div class="modal-header">

                        <h2 class="modal-title" id="modal-title-driver">Image</h2>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                            <span aria-hidden="true">×</span>

                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="row" id="image_row"></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection



