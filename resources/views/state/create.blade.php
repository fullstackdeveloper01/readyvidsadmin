@extends('layouts.app', ['title' => __('State Management')])



@section('content')

    @include('state.partials.header', ['title' => __('Add State')])



    <div class="container-fluid mt--7">

        <div class="row">

            <div class="col-xl-12 order-xl-1">

                <div class="card bg-secondary shadow">

                    <div class="card-header bg-white border-0">

                        <div class="row align-items-center">

                            <div class="col-8">

                                <!--<h3 class="mb-0">{{ __('State Management') }}</h3>-->

                            </div>

                            <div class="col-4 text-right">

                                <a href="{{ route('state.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>

                            </div>

                        </div>

                    </div>

                    <div class="card-body">

                        <!--<h6 class="heading-small text-muted mb-4">{{ __('State information') }}</h6>-->

                        <div class="pl-lg-4">

                            <form method="post" action="{{ route('state.store') }}" autocomplete="off">

                                @csrf

                                </div>

                                <div class="pl-lg-4">

                                    <div class="form-group{{ $errors->has('country_id') ? ' has-danger' : '' }}">

                                        <label class="form-control-label" for="country_id">{{ __('Country Name') }}</label>

                                        <select name="country_id" id="country_id" class="form-control form-control-alternative{{ $errors->has('country_id') ? ' is-invalid' : '' }}"  required>

                                            <option>Select Country</option>

                                        </select>

                                        @if ($errors->has('country_id'))

                                            <span class="invalid-feedback" role="alert">

                                                <strong>{{ $errors->first('country_id') }}</strong>

                                            </span>

                                        @endif

                                    </div>

                                    <div class="form-group{{ $errors->has('state_name') ? ' has-danger' : '' }}">

                                        <label class="form-control-label" for="state_name">{{ __('State Name') }}</label>

                                        <input type="text" name="state_name" id="state_name" class="form-control form-control-alternative{{ $errors->has('state_name') ? ' is-invalid' : '' }}" placeholder="{{ __('State Name') }}" value="" required>
                                        <span id="state_error" style="color:red;"></span>
                                        @if ($errors->has('state_name'))

                                            <span class="invalid-feedback" role="alert">

                                                <strong>{{ $errors->first('state_name') }}</strong>

                                            </span>

                                        @endif

                                    </div>

                                  

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

        <script type="text/javascript">

            $(document).ready(function(){

                $.ajax({

                    url: '/getCountry',

                    type: 'get',

                    dataType: 'json',

                    success: function(response){

                        if(response.data!=""){

                            var html ='';

                            $.each(response.data,function(key,value){

                                html+='<option value="'+value.id+'">'+value.country_name+'</option>'

                            })

                            $('#country_id').html(html);

                        }else{

                            $('#country_id').html('<option value="">No country Found</option>');

                        }

                    }

                });

            })

        </script>

        @include('layouts.footers.auth')

    </div>

@endsection

