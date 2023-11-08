@extends('layouts.app', ['title' => __('Country Management')])



@section('content')

    @include('city.partials.header', ['title' => __('Add Country')])



    <div class="container-fluid mt--7">

        <div class="row">

            <div class="col-xl-12 order-xl-1">

                <div class="card bg-secondary shadow">

                    <div class="card-header bg-white border-0">

                        <div class="row align-items-center">

                            <div class="col-8">

                                <!--<h3 class="mb-0">{{ __('Country Management') }}</h3>-->

                            </div>

                            <div class="col-4 text-right">

                                <a href="{{ route('country.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>

                            </div>

                        </div>

                    </div>

                    <div class="card-body">

                        <!--<h6 class="heading-small text-muted mb-4">{{ __('Country information') }}</h6>-->

                        <div class="pl-lg-4">

                            <form method="post" action="{{ route('country.store') }}" autocomplete="off" enctype="multipart/form-data">

                                @csrf

                                </div>

                                <div class="pl-lg-4">

                                    <div class="col-md-6 form-group{{ $errors->has('country_name') ? ' has-danger' : '' }}">

                                        <label class="form-control-label" for="country_name">{{ __('Country Name') }}</label>

                                        <input type="text" name="country_name" id="country_name" class="form-control form-control-alternative{{ $errors->has('country_name') ? ' is-invalid' : '' }}" placeholder="{{ __('Country Name') }}" value="">
                                        <span id="country_error" style="color:red;"></span>
                                        @if ($errors->has('country_name'))

                                            <span class="invalid-feedback" role="alert">

                                                <strong>{{ $errors->first('country_name') }}</strong>

                                            </span>

                                        @endif

                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Icon</label>
                                        <input type="file" name="icon" class="form-control" required>
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



        @include('layouts.footers.auth')

        <script type="text/javascript">
            $(document).ready(function(){
                $('button[type="submit"]').click(function(){
                    if($('input[name="country_name"]').val()==""){
                        $('#country_error').html('Input Feild Required');
                        return false;
                    }else $('#country_error').html('');
                })
            });
        </script>
    </div>

@endsection

