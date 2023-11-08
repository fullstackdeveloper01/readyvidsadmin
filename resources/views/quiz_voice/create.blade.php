@extends('layouts.app', ['title' => __('Quiz Voice Management')])

@section('content')
    @include('languages.partials.header', ['title' => __('Add Quiz Voice')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <!--<h3 class="mb-0">{{ __('language Management') }}</h3>-->
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('quiz_voice.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--<h6 class="heading-small text-muted mb-4">{{ __('City information') }}</h6>-->
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('quiz_voice.store') }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                </div>
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-md-6 form-group{{ $errors->has('country_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="country_id">{{ __('Country') }}</label>
                                            <select name="country_id" id="country_id" class="form-control form-control-alternative{{ $errors->has('country_id') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                            @foreach($countryList as $res)
                                                <option value="{{$res->id}}">{{$res->country_name}}</option>
                                            @endforeach
                                            
                                            @if ($errors->has('country_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('country_id') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                       
                                        
                                        <div class="col-md-6 form-group{{ $errors->has('voice_text') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="voice_text_m1">{{ __('Voice Text') }}</label>
                                            <input type="text" name="voice_text" id="voice_text" class="form-control form-control-alternative{{ $errors->has('voice_text') ? ' is-invalid' : '' }}" placeholder="{{ __('Voice Text') }}" value="{{old('voice_text')}}" required>
                                            @if ($errors->has('voice_text'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('voice_text') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Speaker Profile Picture</label>
                                            <input type="file" name="speaker_profile_picture" class="form-control" required type="image/*">
                                        </div>
                                       
                                        <div class="col-md-6 form-group">
                                            <label>Voice Sample</label>
                                            <input type="file" name="voice_sample" class="form-control" required type="audio/*">
                                        </div>
                                        
                                   
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
