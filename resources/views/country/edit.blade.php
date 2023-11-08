@extends('layouts.app', ['title' => __('Country Management')])

@section('content')
    @include('city.partials.header', ['title' => __('Edit Country')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Country Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('country.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('country.update', $country) }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <hr />
                                <h6 class="heading-small text-muted mb-4">{{ __('Country information') }}</h6>
                                <div class="pl-lg-4">
                                    <div class="col-md-6 form-group{{ $errors->has('country_name') ? ' has-danger' : '' }}">

                                    <label class="form-control-label" for="country_name">{{ __('Country Name') }}</label>

                                    <input type="text" name="country_name" id="country_name" class="form-control form-control-alternative{{ $errors->has('country_name') ? ' is-invalid' : '' }}" placeholder="{{ __('Country Name') }}" value="{{ old('name', $country->country_name) }}">
                                    <span id="country_error" style="color:red;"></span>
                                    @if ($errors->has('country_name'))

                                        <span class="invalid-feedback" role="alert">

                                            <strong>{{ $errors->first('country_name') }}</strong>

                                        </span>

                                    @endif

                                    </div>
                                    <div class="col-md-6 form-group">
                                    <label>Icon</label>
                                    <input type="file" name="icon" class="form-control">
                                    </div>
                                    <div class="col-md-6  form-group">
                                        <label>Selected Icon</label>
                                        <img width="100px" height="100px" src="{{ asset("{$country->icon}") }}">
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success mt-4">{{ __('Update') }}</button>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
