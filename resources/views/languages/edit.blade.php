@extends('layouts.app', ['title' => __('Language Management')])

@section('content')
    @include('languages.partials.header', ['title' => __('Edit language')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Language Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('languages.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('languages.update', $language) }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <hr />
                                <h6 class="heading-small text-muted mb-4">{{ __('Language information') }}</h6>
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-md-6 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="name">{{ __('Language Name') }}</label>
                                            <input type="text" name="name" id="name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter Party name') }}" value="{{$language->name}}" required>
                                            @if ($errors->has('name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                         <div class="col-md-6 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="name">{{ __('Description') }}</label>
                                                <input type="text" name="description" id="description" class="form-control form-control-alternative{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter description') }}" value="{{old('description')}} {{$language->description}}">
                                                @if ($errors->has('description'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('description') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Icon</label>
                                                <input type="file" name="icon" class="form-control">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Selected Icon</label>
                                                <img src="{{ asset($language->icon) }}" height="100" width="100">
                                            </div>
                                        
                                            <div class="col-md-6 form-group{{ $errors->has('voice_text_m1') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="voice_text_m1">{{ __('Voice Text Male1') }}</label>
                                                <input type="text" name="voice_text_m1" id="voice_text_m1" class="form-control form-control-alternative{{ $errors->has('voice_text_m1') ? ' is-invalid' : '' }}" placeholder="{{ __('Voice Text Male1') }}" value="{{old('voice_text_m1')}} {{$language->voice_text_m1}}" required>
                                                @if ($errors->has('voice_text_m1'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('voice_text_m1') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                            </div>
                                           
                                            <div class="col-md-6 form-group">
                                                <label>Voice Picture Male1</label>
                                                <input type="file" name="audio_m" class="form-control" accept="image/*">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <!--<label>Picture</label>-->
                                                <img src="{{ asset($language->audio_m) }}" height="100" width="100">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Voice Sample Male1</label>
                                                <input type="file" name="voice_upload1" class="form-control" accept="audio/*">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                 <!--<label>Sample</label>-->
                                                <audio controls src="{{ asset($language->voice_upload1) }}" type="audio/*">
                                                </audio>
                                             
                                            </div>
                                             <div class="col-md-6 form-group{{ $errors->has('voice_text_m2') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="voice_text_m1">{{ __('Voice Text Male2') }}</label>
                                                <input type="text" name="voice_text_m2" id="voice_text_m2" class="form-control form-control-alternative{{ $errors->has('voice_text_m2') ? ' is-invalid' : '' }}" placeholder="{{ __('Voice Text Male2') }}" value="{{old('voice_text_m2')}} {{$language->voice_text_m2}}" required>
                                                @if ($errors->has('voice_text_m2'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('voice_text_m2') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                             <div class="col-md-6">
                                            </div>
                                           
                                            <div class="col-md-6 form-group">
                                                <label>Voice Picture Male2</label>
                                                <input type="file" name="audio_m1" class="form-control" accept="image/*">
                                            </div>
                                             <div class="col-md-6 form-group">
                                               
                                                <img src="{{ asset($language->audio_m1) }}" height="100" width="100">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Voice Sample Male2</label>
                                                <input type="file" name="voice_upload2" class="form-control" accept="audio/*">
                                            </div> 
                                            <div class="col-md-6 form-group">
                                                 <!--<label>Sample</label>-->
                                                <audio controls src="{{ asset($language->voice_upload2) }}" type="audio/*">
                                                </audio>
                                             
                                            </div>
                                             <div class="col-md-6 form-group{{ $errors->has('voice_text_m3') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="voice_text_m1">{{ __('Voice Text Male3') }}</label>
                                                <input type="text" name="voice_text_m3" id="voice_text_m3" class="form-control form-control-alternative{{ $errors->has('voice_text_m3') ? ' is-invalid' : '' }}" placeholder="{{ __('Voice Text Male3') }}" value="{{old('voice_text_m3')}} {{$language->voice_text_m3}}" required>
                                                @if ($errors->has('voice_text_m3'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('voice_text_m3') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Voice Picture Male3</label>
                                                <input type="file" name="audio_m2" class="form-control" accept="image/*">
                                            </div>
                                             <div class="col-md-6 form-group">
                                                <!--<label>Picture</label>-->
                                                <img src="{{ asset($language->audio_m2) }}" height="100" width="100">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Voice Sample Male3</label>
                                                <input type="file" name="voice_upload3" class="form-control" accept="audio/*">
                                            </div>
                                             <div class="col-md-6 form-group">
                                                 <!--<label>Sample</label>-->
                                                <audio controls src="{{ asset($language->voice_upload3) }}" type="audio/*">
                                                </audio>
                                             
                                            </div>
                                             <div class="col-md-6 form-group{{ $errors->has('voice_text_m4') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="voice_text_m4">{{ __('Voice Text Male4') }}</label>
                                                <input type="text" name="voice_text_m4" id="voice_text_m4" class="form-control form-control-alternative{{ $errors->has('voice_text_m4') ? ' is-invalid' : '' }}" placeholder="{{ __('Voice Text Male4') }}" value="{{old('voice_text_m4')}} {{$language->voice_text_m4}}" required>
                                                @if ($errors->has('voice_text_m4'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('voice_text_m4') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Voice Picture Male4</label>
                                                <input type="file" name="audio_m3" class="form-control" accept="image/*">
                                            </div>
                                             <div class="col-md-6 form-group">
                                                <!--<label>Picture</label>-->
                                                <img src="{{ asset($language->audio_m3) }}" height="100" width="100">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Voice Sample Male4</label>
                                                <input type="file" name="voice_upload4" class="form-control" accept="audio/*">
                                            </div>
                                             <div class="col-md-6 form-group">
                                                 <!--<label>Sample</label>-->
                                                <audio controls src="{{ asset($language->voice_upload4) }}" type="audio/*">
                                                </audio>
                                             
                                            </div>
                                             <div class="col-md-6 form-group{{ $errors->has('voice_text_m5') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="voice_text_m5">{{ __('Voice Text Male5') }}</label>
                                                <input type="text" name="voice_text_m5" id="voice_text_m5" class="form-control form-control-alternative{{ $errors->has('voice_text_m5') ? ' is-invalid' : '' }}" placeholder="{{ __('Voice Text Male5') }}" value="{{old('voice_text_m5')}} {{$language->voice_text_m5}}" required>
                                                @if ($errors->has('voice_text_m5'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('voice_text_m5') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                             <div class="col-md-6">
                                            </div>
                                           
                                            <div class="col-md-6 form-group">
                                                <label>Voice Picture Male5</label>
                                                <input type="file" name="audio_m4" class="form-control" accept="image/*">
                                            </div>
                                             <div class="col-md-6 form-group">
                                                <!--<label>Picture</label>-->
                                                <img src="{{ asset($language->audio_m4) }}" height="100" width="100">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Voice Sample Male5</label>
                                                <input type="file" name="voice_upload5" class="form-control" accept="audio/*">
                                            </div> 
                                             <div class="col-md-6 form-group">
                                                 <!--<label>Sample</label>-->
                                                <audio controls src="{{ asset($language->voice_upload5) }}" type="audio/*">
                                                </audio>
                                             
                                            </div>
                                            
                                            
                                            
                                              <div class="col-md-6 form-group{{ $errors->has('voice_text_m6') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="voice_text_m6">{{ __('Voice Text Female1') }}</label>
                                                <input type="text" name="voice_text_m6" id="voice_text_m6" class="form-control form-control-alternative{{ $errors->has('voice_text_m6') ? ' is-invalid' : '' }}" placeholder="{{ __('Voice Text Female1') }}" value="{{old('voice_text_m6')}} {{$language->voice_text_f1}}" required>
                                                @if ($errors->has('voice_text_m6'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('voice_text_m6') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Voice Picture Female1</label>
                                                <input type="file" name="audio_f" class="form-control" accept="image/*">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <!--<label>Picture</label>-->
                                                <img src="{{ asset($language->audio_f) }}" height="100" width="100">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Voice Sample Female1</label>
                                                <input type="file" name="voice_upload6" class="form-control" accept="audio/*">
                                            </div>
                                             <div class="col-md-6 form-group">
                                                 <!--<label>Sample</label>-->
                                                <audio controls src="{{ asset($language->voice_upload6) }}" type="audio/*">
                                                </audio>
                                             
                                            </div>
                                             <div class="col-md-6 form-group{{ $errors->has('voice_text_m7') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="voice_text_m1">{{ __('Voice Text Female2') }}</label>
                                                <input type="text" name="voice_text_m7" id="voice_text_m7" class="form-control form-control-alternative{{ $errors->has('voice_text_m7') ? ' is-invalid' : '' }}" placeholder="{{ __('Voice Text Female2') }}" value="{{old('voice_text_m7')}} {{$language->voice_text_f2}}" required>
                                                @if ($errors->has('voice_text_m7'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('voice_text_m7') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Voice Picture Female2</label>
                                                <input type="file" name="audio_f1" class="form-control" accept="image/*">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <!--<label>Picture</label>-->
                                                <img src="{{ asset($language->audio_f1) }}" height="100" width="100">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Voice Sample Female2</label>
                                                <input type="file" name="voice_upload7" class="form-control" accept="audio/*">
                                            </div>
                                             <div class="col-md-6 form-group">
                                                 <!--<label>Sample</label>-->
                                                <audio controls src="{{ asset($language->voice_upload7) }}" type="audio/*">
                                                </audio>
                                             
                                            </div>
                                             <div class="col-md-6 form-group{{ $errors->has('voice_text_m8') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="voice_text_m1">{{ __('Voice Text Female3') }}</label>
                                                <input type="text" name="voice_text_m8" id="voice_text_m8" class="form-control form-control-alternative{{ $errors->has('voice_text_m8') ? ' is-invalid' : '' }}" placeholder="{{ __('Voice Text Female3') }}" value="{{old('voice_text_m8')}} {{$language->voice_text_f3}}" required>
                                                @if ($errors->has('voice_text_m8'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('voice_text_m8') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                           <div class="col-md-6">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Voice Picture Female3</label>
                                                <input type="file" name="audio_f2" class="form-control" accept="image/*">
                                            </div>
                                             <div class="col-md-6 form-group">
                                                <!--<label>Picture</label>-->
                                                <img src="{{ asset($language->audio_f2) }}" height="100" width="100">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Voice Sample Female3</label>
                                                <input type="file" name="voice_upload8" class="form-control" accept="audio/*">
                                            </div>
                                             <div class="col-md-6 form-group">
                                                 <!--<label>Sample</label>-->
                                                <audio controls src="{{ asset($language->voice_upload8) }}" type="audio/*">
                                                </audio>
                                             
                                            </div>
                                             <div class="col-md-6 form-group{{ $errors->has('voice_text_m9') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="voice_text_m9">{{ __('Voice Text Female4') }}</label>
                                                <input type="text" name="voice_text_m9" id="voice_text_m9" class="form-control form-control-alternative{{ $errors->has('voice_text_m9') ? ' is-invalid' : '' }}" placeholder="{{ __('Voice Text Female4') }}" value="{{old('voice_text_m9')}} {{$language->voice_text_f4}}" required>
                                                @if ($errors->has('voice_text_m9'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('voice_text_m9') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                             <div class="col-md-6">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Voice Picture Female4</label>
                                                <input type="file" name="audio_f3" class="form-control" accept="image/*">
                                            </div>
                                             <div class="col-md-6 form-group">
                                                <!--<label>Picture</label>-->
                                                <img src="{{ asset($language->audio_f3) }}" height="100" width="100">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Voice Sample Female4</label>
                                                <input type="file" name="voice_upload9" class="form-control" accept="audio/*">
                                            </div>
                                             <div class="col-md-6 form-group">
                                                 <!--<label>Sample</label>-->
                                                <audio controls src="{{ asset($language->voice_upload9) }}" type="audio/*">
                                                </audio>
                                             
                                            </div>
                                             <div class="col-md-6 form-group{{ $errors->has('voice_text_m10') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="voice_text_m10">{{ __('Voice Text Female10') }}</label>
                                                <input type="text" name="voice_text_m10" id="voice_text_m10" class="form-control form-control-alternative{{ $errors->has('voice_text_m10') ? ' is-invalid' : '' }}" placeholder="{{ __('Voice Text Female5') }}" value="{{old('voice_text_m10')}} {{$language->voice_text_f5}}" required>
                                                @if ($errors->has('voice_text_m10'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('voice_text_m10') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Voice Picture Female5</label>
                                                <input type="file" name="audio_f4" class="form-control" accept="image/*">
                                            </div>
                                             <div class="col-md-6 form-group">
                                                <!--<label>Picture</label>-->
                                                <img src="{{ asset($language->audio_f4) }}" height="100" width="100">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Voice Sample Female5</label>
                                                <input type="file" name="voice_upload10" class="form-control" accept="audio/*">
                                            </div> 
                                            <div class="col-md-6 form-group">
                                                 <!--<label>Sample</label>-->
                                                <audio controls src="{{ asset($language->voice_upload10) }}" type="audio/*">
                                                </audio>
                                             
                                            </div>
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
