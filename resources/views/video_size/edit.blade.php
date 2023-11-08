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
                                <a href="{{ route('video_size.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">                                
                        <form method="post" action="{{ route('video_size.update', $video_size) }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                            <hr />
                            <h6 class="heading-small text-muted mb-4">{{ __('Ratio information') }}</h6>
                            <input type="hidden" name="id" value="{{$video_size->id}}">
                            <div class="pl-lg-4">
                                <div class="col-md-6 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative" placeholder="{{ __('ratio name') }}" value="{{ old('name', $video_size->name) }}">
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="name">{{ __('Description') }}</label>
                                        <input type="text" name="description" id="description" class="form-control form-control-alternative{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Video Size description') }}" value="{{ old('description', $video_size->description) }}" required>
                                        @if ($errors->has('description'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6 form-group{{ $errors->has('display_video') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="name">{{ __('Video Size') }}</label>
                                        <input type="number" name="display_video" id="display_video" class="form-control form-control-alternative{{ $errors->has('display_video') ? ' is-invalid' : '' }}" placeholder="{{ __('Video Size') }}" value="{{old('display_video', $video_size->display_video)}}" required>
                                        @if ($errors->has('display_video'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('display_video') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6 form-group{{ $errors->has('question_display_time') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="name">{{ __('Question Display Time') }}</label>
                                        <input type="number" name="question_display_time" id="question_display_time" class="form-control form-control-alternative{{ $errors->has('question_display_time') ? ' is-invalid' : '' }}" placeholder="{{ __('Video size question display time') }}" value="{{ old('question_display_time', $video_size->question_display_time) }}" required>
                                        @if ($errors->has('question_display_time'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('question_display_time') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6 form-group{{ $errors->has('answer_display_time') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="name">{{ __('Answer Display Time') }}</label>
                                        <input type="number" name="answer_display_time" id="answer_display_time" class="form-control form-control-alternative{{ $errors->has('answer_display_time') ? ' is-invalid' : '' }}" placeholder="{{ __('Video size answer display time') }}" value="{{ old('answer_display_time', $video_size->answer_display_time) }}" required>
                                        @if ($errors->has('answer_display_time'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('answer_display_time') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Icon</label>
                                        <input type="file" name="icon" class="form-control">
                                    </div>
                                    <div class="col-md-6  form-group">
                                        <label>Selected Icon</label>
                                        <img width="100px" height="100px" src="{{ asset("{$video_size->image}") }}">
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
