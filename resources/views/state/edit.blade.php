@extends('layouts.app', ['title' => __('City Management')])

@section('content')
    @include('city.partials.header', ['title' => __('Edit City')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('City Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('city.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('city.update', $city) }}" autocomplete="off">
                                @csrf
                                @method('put')
                                <hr />
                                <h6 class="heading-small text-muted mb-4">{{ __('City information') }}</h6>
                                <div class="pl-lg-4">
                                    <div class="form-group{{ $errors->has('city_name') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-name">{{ __('City Name') }}</label>
                                        <input type="text" name="city_name" id="input-name" class="form-control form-control-alternative" placeholder="{{ __('City Name') }}" value="{{ old('city_name', $city->city_name) }}" >
                                    </div>
                                    <div class="form-group{{ $errors->has('city_name') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-name">{{ __('City Status') }}</label>
                                        <select name="city_status" id="city_status" class="form-control form-control-alternative{{ $errors->has('city_status') ? ' is-invalid' : '' }}" required value="{{ old('city_status', $city->active) }}">
                                            <option value="1" @if($city->active==1) selected @endif>Active</option>
                                            <option value="0" @if($city->active==0) selected @endif>Inactive</option>
                                        </select>
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
