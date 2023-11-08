@extends('layouts.app', ['title' => __('Ratio Management')])

@section('content')
    @include('ratio.partials.header', ['title' => __('Edit Ratio')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Ratio Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('quiz_ratio.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">                                
                        <form method="post" action="{{ route('quiz_ratio.update', $quizratio) }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                            <hr />
                            <h6 class="heading-small text-muted mb-4">{{ __('Ratio information') }}</h6>
                            <input type="hidden" name="id" value="{{$quizratio->id}}">
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative" placeholder="{{ __('ratio name') }}" value="{{ old('name', $quizratio->name) }}">
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('value') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="value">{{ __('Ratio') }}</label>
                                    <input type="text" name="value" id="value" class="form-control form-control-alternative" placeholder="{{ __('ratio value') }}" value="{{ old('value', $quizratio->value) }}">
                                    @if ($errors->has('value'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('value') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Icon</label>
                                    <input type="file" name="icon" class="form-control">
                                </div>
                                <div class="col-md-6  form-group">
                                    <label>Selected Icon</label>
                                    <img width="100px" height="100px" src="{{ asset("{$quizratio->image}") }}">
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
