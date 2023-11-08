@extends('layouts.app', ['name' => __('Voice Management')])

@section('content')
    @include('voice.partials.header', ['name' => __('Upload Multiple Voice')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Voice Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('voice.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                     <div class="col-12">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">{{ __('Voice information') }}</h6>
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('voice.store') }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                </div>
                                <div class="pl-lg-4">
                                    <div class="form-group{{ $errors->has('parent_id') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="parent_id">{{ __('Folder Name') }}</label>
                                        <select name="folder_id" id="folder_id" class="form-control form-control-alternative{{ $errors->has('folder_id') ? ' is-invalid' : '' }}" required>
                                            <option value=""> -- </option>
                                        @foreach($folders as $res)
                                            @if (session('folder_id'))
                                                <option value="{{$res->id}}"  @if(session('folder_id')==$res->id) selected @endif>{{$res->folder_name}}</option>
                                            @else
                                                <option value="{{$res->id}}">{{$res->folder_name}}</option>
                                            @endif
                                        @endforeach
                                        
                                        @if ($errors->has('folder_id'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('folder_id') }}</strong>
                                            </span>
                                        @endif
                                        </select>
                                    </div>
                                    
                                    <div class="form-group{{ $errors->has('video_size') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="video_size">{{ __('Video Size') }}</label>
                                        <select name="video_size" id="video_size" class="form-control form-control-alternative{{ $errors->has('video_size') ? ' is-invalid' : '' }}" required>
                                            <option value=""> -- </option>
                                            @if (session('video_size'))
                                                <option value="short" @if (session('video_size')=='short') selected @endif>Short Video</option>
                                                <option value="long" @if (session('video_size')=='long') selected @endif>Long Video</option>
                                            @else
                                                <option value="short">Short Video</option>
                                                <option value="long">Long Video</option>
                                            @endif
                                           
                                        
                                            @if ($errors->has('video_size'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('video_size') }}</strong>
                                                </span>
                                            @endif
                                        </select>
                                    </div>
                                    
                                    <div class="form-group{{ $errors->has('voice_type') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="voice_type">{{ __('Voice Type') }}</label>
                                        <select name="voice_type" id="voice_type" class="form-control form-control-alternative{{ $errors->has('voice_type') ? ' is-invalid' : '' }}" required>
                                            <option value=""> -- </option>
                                            @if (session('voice_type') && session('video_size')=='short')
                                            <option value="audio_f1" @if (session('voice_type')=='audio_f1') selected @endif>Female 1</option>
                                            <option value="audio_f2" @if (session('voice_type')=='audio_f2') selected @endif>Female 2</option>
                                            <option value="audio_f3" @if (session('voice_type')=='audio_f3') selected @endif>Female 3</option>
                                            <option value="audio_f4" @if (session('voice_type')=='audio_f4') selected @endif>Female 4</option>
                                            <option value="audio_f5" @if (session('voice_type')=='audio_f5') selected @endif>Female 5</option>
                                            <option value="audio_m1" @if (session('voice_type')=='audio_m1') selected @endif>Male 1</option>
                                            <option value="audio_m2" @if (session('voice_type')=='audio_m2') selected @endif>Male 2</option>
                                            <option value="audio_m3" @if (session('voice_type')=='audio_m3') selected @endif>Male 3</option>
                                            <option value="audio_m4" @if (session('voice_type')=='audio_m4') selected @endif>Male 4</option>
                                            <option value="audio_m5" @if (session('voice_type')=='audio_m5') selected @endif>Male 5</option>
                                            @elseif (session('voice_type') && session('video_size')=='long')
                                            <option value="audio_f1" @if (session('voice_type')=='audio_f1_long') selected @endif>Female 1</option>
                                            <option value="audio_f2" @if (session('voice_type')=='audio_f2_long') selected @endif>Female 2</option>
                                            <option value="audio_f3" @if (session('voice_type')=='audio_f3_long') selected @endif>Female 3</option>
                                            <option value="audio_f4" @if (session('voice_type')=='audio_f4_long') selected @endif>Female 4</option>
                                            <option value="audio_f5" @if (session('voice_type')=='audio_f5_long') selected @endif>Female 5</option>
                                            <option value="audio_m1" @if (session('voice_type')=='audio_m1_long') selected @endif>Male 1</option>
                                            <option value="audio_m2" @if (session('voice_type')=='audio_m2_long') selected @endif>Male 2</option>
                                            <option value="audio_m3" @if (session('voice_type')=='audio_m3_long') selected @endif>Male 3</option>
                                            <option value="audio_m4" @if (session('voice_type')=='audio_m4_long') selected @endif>Male 4</option>
                                            <option value="audio_m5" @if (session('voice_type')=='audio_m5_long') selected @endif>Male 5</option>
                                            @else
                                             <option value="audio_f1">Female 1</option>
                                            <option value="audio_f2">Female 2</option>
                                            <option value="audio_f3">Female 3</option>
                                            <option value="audio_f4">Female 4</option>
                                            <option value="audio_f5">Female 5</option>
                                            <option value="audio_m1">Male 1</option>
                                            <option value="audio_m2">Male 2</option>
                                            <option value="audio_m3">Male 3</option>
                                            <option value="audio_m4">Male 4</option>
                                            <option value="audio_m5">Male 5</option>
                                            @endif
                                        
                                            @if ($errors->has('voice_type'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('voice_type') }}</strong>
                                                </span>
                                            @endif
                                        </select>
                                    </div>
                                    
                                    
                                     
                                   
                                    <div class="form-group">
                                        <label class="form-control-label" for="name">{{ __('Voice') }}</label>
                                        <input type="file" name="files[]" placeholder="Choose files" multiple class="form-control" required accept="audio/*">
                                        @if ($errors->has('files'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('files') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="voice_name">{{ __('Voice Name') }}</label>
                                        <input type="file" name="voice_name" class="form-control" required>
                                        @if ($errors->has('voice_name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('voice_name') }}</strong>
                                            </span>
                                        @endif
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

