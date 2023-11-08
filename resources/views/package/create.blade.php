@extends('layouts.app', ['title' => __('Package Management')])



@section('content')

    @include('package.partials.header', ['title' => __('Add Package')])



    <div class="container-fluid mt--7">

        <div class="row">

            <div class="col-xl-12 order-xl-1">

                <div class="card bg-secondary shadow">

                    <div class="card-header bg-white border-0">

                        <div class="row align-items-center">

                            <div class="col-8">

                                <!--<h3 class="mb-0">{{ __('Package Management') }}</h3>-->

                            </div>

                            <div class="col-4 text-right">

                                <a href="{{ route('package.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>

                            </div>

                        </div>

                    </div>

                    <div class="card-body">

                        <!--<h6 class="heading-small text-muted mb-4">{{ __('Package information') }}</h6>-->

                        <div class="pl-lg-4">

                            <form method="post" action="{{ route('package.store') }}" autocomplete="off">

                                @csrf

                                </div>

                                <div class="pl-lg-4">

                                    <div class="row">

                                        <div class=" col-md-6 form-group{{ $errors->has('[package_title') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="package_title">{{ __('Title') }}</label>

                                            <input type="text" name="package_title" id="package_title" class="form-control form-control-alternative{{ $errors->has('package_title') ? ' is-invalid' : '' }}" placeholder="{{ __('Package Title') }}" value="" required>

                                            @if ($errors->has('package_title'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('package_title') }}</strong>

                                                </span>

                                            @endif

                                        </div>

                                        <div class="col-md-6 form-group{{ $errors->has('[package_price') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="package_price">{{ __('Price') }}</label>

                                            <input type="number" name="package_price" id="package_price" class="form-control  form-control-alternative{{ $errors->has('package_price') ? ' is-invalid' : '' }}" placeholder="{{ __('Package Price') }}" value="" required>

                                            @if ($errors->has('package_price'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('package_price') }}</strong>

                                                </span>

                                            @endif

                                        </div>

                                        <div class=" col-md-6 form-group{{ $errors->has('[short_video') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="short_video">{{ __('Short Videos Limit') }}</label>

                                            <input type="text" name="short_video" id="short_video" class="form-control form-control-alternative{{ $errors->has('short_video') ? ' is-invalid' : '' }}" placeholder="{{ __('Short Videos Limit') }}" value="" required>

                                            @if ($errors->has('short_video'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('short_video') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        
                                        <div class=" col-md-6 form-group{{ $errors->has('[long_video') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="long_video">{{ __('Long Videos Limit') }}</label>

                                            <input type="text" name="long_video" id="long_video" class="form-control form-control-alternative{{ $errors->has('long_video') ? ' is-invalid' : '' }}" placeholder="{{ __('Long Videos Limit') }}" value="{{old('long_video')}}" required>

                                            @if ($errors->has('long_video'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('long_video') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        
                                         <div class=" col-md-6 form-group{{ $errors->has('[video_type') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="video_type">{{ __('Videos Type') }}</label>

                                            <!--<input type="text" name="video_type" id="video_type" class="form-control number form-control-alternative{{ $errors->has('video_type') ? ' is-invalid' : '' }}" placeholder="{{ __('Videos Type') }}" value="{{old('video_type')}}" required>-->
                                            <select name="video_type" id="video_type" class="form-control form-control-alternative{{ $errors->has('video_type') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                                <option value="SD Video">SD Video</option>
                                                <option value="HD Video">HD Video</option>
                                                
                                                @if ($errors->has('video_type'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('video_type') }}</strong>
                                                    </span>
                                                @endif
                                            </select>
                                           
                                        </div>
                                        
                                        <div class=" col-md-6 form-group{{ $errors->has('[support') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="support">{{ __('Support') }}</label>
                                            <select name="support" id="support" class="form-control form-control-alternative{{ $errors->has('support') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                                <option value="Normal Support">Normal Support</option>
                                                <option value="Priority Support">Priority Support</option>
                                                
                                                @if ($errors->has('support'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('support') }}</strong>
                                                    </span>
                                                @endif
                                            </select>
                                            <!--<input type="text" name="support" id="support" class="form-control number form-control-alternative{{ $errors->has('support') ? ' is-invalid' : '' }}" placeholder="{{ __('Support') }}" value="{{old('support')}}" required>-->

                                            <!--@if ($errors->has('support'))-->

                                            <!--    <span class="invalid-feedback" role="alert">-->

                                            <!--        <strong>{{ $errors->first('support') }}</strong>-->

                                            <!--    </span>-->

                                            <!--@endif-->

                                        </div>
                                        
                                        <div class=" col-md-6 form-group{{ $errors->has('[company_logo') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="company_logo">{{ __('company_logo') }}</label>

                                            <select name="company_logo" id="company_logo" class="form-control form-control-alternative{{ $errors->has('company_logo') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                                
                                                @if ($errors->has('company_logo'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('company_logo') }}</strong>
                                                    </span>
                                                @endif
                                            </select>

                                        </div>
                                        
                                         <div class=" col-md-6 form-group{{ $errors->has('[add_logo') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="add_logo">{{ __('Add Logo') }}</label>

                                            <select name="add_logo" id="add_logo" class="form-control form-control-alternative{{ $errors->has('add_logo') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                                
                                                @if ($errors->has('add_logo'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('add_logo') }}</strong>
                                                    </span>
                                                @endif
                                            </select>

                                        </div>
                                        
                                         <div class=" col-md-6 form-group{{ $errors->has('[add_intro') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="add_intro">{{ __('Add Intro') }}</label>

                                            <select name="add_intro" id="add_intro" class="form-control form-control-alternative{{ $errors->has('add_intro') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                                
                                                @if ($errors->has('add_intro'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('add_intro') }}</strong>
                                                    </span>
                                                @endif
                                            </select>

                                        </div>
                                        
                                         <div class=" col-md-6 form-group{{ $errors->has('[add_outro') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="add_outro">{{ __('Add Outro') }}</label>

                                            <select name="add_outro" id="add_outro" class="form-control form-control-alternative{{ $errors->has('add_outro') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                                
                                                @if ($errors->has('add_outro'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('add_outro') }}</strong>
                                                    </span>
                                                @endif
                                            </select>

                                        </div>
                                        
                                         
                                        
                                        <div class=" col-md-6 form-group{{ $errors->has('short_video_limit') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="short_video_limit">{{ __('Daily Short Video Limit') }}</label>

                                            <input type="text" name="short_video_limit" id="short_video_limit" class="form-control form-control-alternative{{ $errors->has('short_video_limit') ? ' is-invalid' : '' }}" placeholder="{{ __('Daily Short Video Limit') }}" value="{{old('short_video_limit')}}" required>

                                            @if ($errors->has('short_video_limit'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('short_video_limit') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        
                                        <div class=" col-md-6 form-group{{ $errors->has('long_video_limit') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="long_video_limit">{{ __('Daily Long Video Limit') }}</label>

                                            <input type="text" name="long_video_limit" id="long_video_limit" class="form-control form-control-alternative{{ $errors->has('long_video_limit') ? ' is-invalid' : '' }}" placeholder="{{ __('Daily Long Video Limit') }}" value="{{old('long_video_limit')}}" required>

                                            @if ($errors->has('long_video_limit'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('long_video_limit') }}</strong>

                                                </span>

                                            @endif

                                        </div>

                                        <div class=" col-md-6 form-group{{ $errors->has('[team_collaboration') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="team_collaboration">{{ __('Team Collaboration') }}</label>

                                            <select name="team_collaboration" id="team_collaboration" class="form-control form-control-alternative{{ $errors->has('team_collaboration') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                                
                                                @if ($errors->has('team_collaboration'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('team_collaboration') }}</strong>
                                                    </span>
                                                @endif
                                            </select>

                                        </div>

                                        <!-- <div class="col-md-6 form-group{{ $errors->has('[extra_hour_price') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="extra_hour_price">{{ __('Extra Hour Price') }}</label>

                                            <input type="text" name="extra_hour_price" id="extra_hour_price" class="form-control hours form-control-alternative{{ $errors->has('extra_hour_price') ? ' is-invalid' : '' }}" placeholder="{{ __('Hour Price') }}" value="" required>

                                            @if ($errors->has('extra_hour_price'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('extra_hour_price') }}</strong>

                                                </span>

                                            @endif

                                        </div> -->
<!--
                                        <div class="col-md-6 form-group{{ $errors->has('[package_hours') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="package_hours">{{ __('Hours') }}</label>

                                            <input type="text" name="package_hours" id="package_hours" class="form-control number form-control-alternative{{ $errors->has('package_hours') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter Hours') }}" value="" required>

                                            @if ($errors->has('package_hours'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('package_hours') }}</strong>

                                                </span>

                                            @endif

                                        </div>-->
<!-- 
                                        <div class="col-md-6 form-group{{ $errors->has('[package_color') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="package_color">{{ __('Color') }}</label>

                                            <input type="color" name="package_color" id="package_color" class="form-control form-control-alternative{{ $errors->has('package_color') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('package_color'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('package_color') }}</strong>

                                                </span>

                                            @endif

                                        </div> -->

                                   

                                    <div class="col-md-6 form-group{{ $errors->has('[package_description') ? ' has-danger' : '' }}">

                                        <label class="form-control-label" for="package_description">{{ __('Description') }}</label>

                                        <textarea name="package_description" id="package_description" class="form-control form-control-alternative{{ $errors->has('package_description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" required></textarea>

                                        @if ($errors->has('package_description'))

                                            <span class="invalid-feedback" role="alert">

                                                <strong>{{ $errors->first('package_description') }}</strong>

                                            </span>

                                        @endif

                                    </div>                              
 </div>    
                                    <div class="text-center">

                                        <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

        <script type="text/javascript">

            $('.number').keyup(function(e) {

                if(this.value == 0) this.value =this.value.replace(/[^1-9\.]/g,'');
                else if(this.value < 0) this.value =this.value.replace(/[^0-9\.]/g,''); 
                else this.value =this.value.replace(/[^0-9\.]/g,'');               

                if(this.value.length > 2) { 
                    $(this).val($(this).attr('data-previous'));
                }else{
                    $(this).attr('data-previous',this.value);
                }
            });

            $('.hours').keyup(function(e) {
                if(this.value == 0) this.value =this.value.replace(/[^1-9\.]/g,'');
                else if(this.value < 0) this.value =this.value.replace(/[^0-9\.]/g,''); 
                else this.value =this.value.replace(/[^0-9\.]/g,'');               
            });

            // $("#package_hours").change(function () {
            //     var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
            //     if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            //         alert("Only formats are allowed : "+fileExtension.join(', '));
            //     }
            // });

        </script>

        @include('layouts.footers.auth')

    </div>

@endsection

