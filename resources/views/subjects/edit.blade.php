@extends('layouts.app', ['title' => __('Subject Management')])

@section('content')
    @include('categories.partials.header', ['title' => __('Edit Subject')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Subject Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('subjects.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">                                
                        <form method="post" action="{{ route('subjects.update', $subject) }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                            <hr />
                            <h6 class="heading-small text-muted mb-4">{{ __('Subject information') }}</h6>
                            <div class="pl-lg-4">
                               
                                <div class="form-group{{ $errors->has('country_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="country_id">{{ __('Country Name') }}</label>
                                    <select name="country_id" id="country_id" class="form-control form-control-alternative{{ $errors->has('country_id') ? ' is-invalid' : '' }}" required>
                                        <option value=""> -- </option>
                                    @foreach($countryList as $res)
                                        <option value="{{$res->id}}" @if($res->id==$subject->country_id) selected @endif>{{$res->country_name}}</option>
                                    @endforeach
                                    
                                    @if ($errors->has('country_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('country_id') }}</strong>
                                        </span>
                                    @endif
                                    </select>
                                </div>
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative" placeholder="{{ __('Subject name') }}" value="{{ old('name', $subject->name) }}">
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Icon</label>
                                    <input type="file" name="icon" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Selected Icon</label>
                                    <img width="100px" height="100px" src="{{ asset("{$subject->icon}") }}">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Update') }}</button>
                                </div>                                
                            </div>    
                        </from>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
