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
                                <a href="{{ route('question_answer_voice.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
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
                            <form method="post" action="{{ route('question_answer_voice.store') }}" autocomplete="off" enctype="multipart/form-data">
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
                                    
                                    <div class="form-group{{ $errors->has('question_answer_voice') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="question_answer_voice">{{ __('Question Answer') }}</label>
                                        <select name="question_answer_voice" id="question_answer_voice" class="form-control form-control-alternative{{ $errors->has('question_answer_voice') ? ' is-invalid' : '' }}" required>
                                            <option value=""> -- </option>
                                            @if (session('question_answer_voice'))
                                                <option value="question" @if (session('question_answer_voice')=='question') selected @endif>1 Introduction Question</option>
                                                <option value="answer" @if (session('question_answer_voice')=='answer') selected @endif>1 Introduction Answer</option>
                                            @else
                                                <option value="question">1 Introduction Question</option>
                                                <option value="answer">1 Introduction Answer</option>
                                            @endif
                                           
                                        
                                            @if ($errors->has('question_answer_voice'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('question_answer_voice') }}</strong>
                                                </span>
                                            @endif
                                        </select>
                                    </div>
                                    
                                    <div class="form-group{{ $errors->has('voice_type') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="voice_type">{{ __('Voice Type') }}</label>
                                        <select name="voice_type" id="voice_type" class="form-control form-control-alternative{{ $errors->has('voice_type') ? ' is-invalid' : '' }}" required>
                                            <option value=""> -- </option>
                                            
                                            @for($index=1;$index<21;$index++)
                                                @php 
                                                    $audio_name = 'audio'.$index;
                                                    $answer_audio_name = 'answer_audio'.$index;
                                                @endphp
                                                
                                                @if(session('voice_type') && session('question_answer_voice')=='question')
                                                    <option value="audio{{$index}}"  @if (session('voice_type')==$audio_name) selected @endif>Voice {{$index}}</option>
                                                @elseif (session('voice_type') && session('question_answer_voice')=='answer')
                                                    <option value="audio{{$index}}"  @if (session('voice_type')==$answer_audio_name) selected @endif>Voice {{$index}}</option>
                                                @else
                                                     <option value="audio{{$index}}">Voice {{$index}}</option>
                                                @endif
                                                
                                            @endfor
                                          
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

