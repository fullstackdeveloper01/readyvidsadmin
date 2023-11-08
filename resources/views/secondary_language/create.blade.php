@extends('layouts.app', ['name' => __('Secondary Language Management')])

@section('content')
    @include('secondary_language.partials.header', ['name' => __('Add Secondary Language')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Secondary Language Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('secondary_language.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">{{ __('Secondary Language information') }}</h6>
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('secondary_language.store') }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                </div>
                                <div class="pl-lg-4">
                                    <div class="form-group{{ $errors->has('primary_language') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="primary_language">{{ __('Primary Language') }}</label>
                                        <select name="primary_language" id="primary_language" class="form-control form-control-alternative{{ $errors->has('primary_language') ? ' is-invalid' : '' }}" required>
                                            <option value=""> -- </option>
                                        @foreach($primaryLanguageList as $res)
                                            <option value="{{$res->id}}">{{$res->name}}</option>
                                        @endforeach
                                        
                                        @if ($errors->has('primary_language'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('primary_language') }}</strong>
                                            </span>
                                        @endif
                                        </select>
                                    </div>
                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="name">{{ __('Secondary Language') }}</label>
                                        <input type="text" name="name" id="name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Secondary  Language name') }}" value="" required>
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                     <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="name">{{ __('Description') }}</label>
                                        <input type="text" name="description" id="description" class="form-control form-control-alternative{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter description') }}" value="{{old('description')}}">
                                        @if ($errors->has('description'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Icon</label>
                                        <input type="file" name="icon" class="form-control" required>
                                    </div>
                                   
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
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

