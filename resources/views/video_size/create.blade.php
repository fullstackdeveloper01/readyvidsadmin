@extends('layouts.app', ['name' => __('Video Size Management')])

@section('content')
    @include('video_size.partials.header', ['name' => __('Add Video Size')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Video Size Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('video_size.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">{{ __('Video Size information') }}</h6>
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('video_size.store') }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                </div>
                                <div class="pl-lg-4">
                                    <div class="col-md-6 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="name">{{ __('Name') }}</label>
                                        <input type="text" name="name" id="name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Video Size Name') }}" value="" required>
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6 form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="name">{{ __('Description') }}</label>
                                        <input type="text" name="description" id="description" class="form-control form-control-alternative{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Video Size description') }}" value="" required>
                                        @if ($errors->has('description'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6 form-group{{ $errors->has('display_video') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="name">{{ __('Video Size') }}</label>
                                        <input type="number" name="display_video" id="display_video" class="form-control form-control-alternative{{ $errors->has('display_video') ? ' is-invalid' : '' }}" placeholder="{{ __('Video Size') }}" value="" required>
                                        @if ($errors->has('display_video'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('display_video') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6 form-group{{ $errors->has('question_display_time') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="name">{{ __('Question Display Time') }}</label>
                                        <input type="number" name="question_display_time" id="question_display_time" class="form-control form-control-alternative{{ $errors->has('question_display_time') ? ' is-invalid' : '' }}" placeholder="{{ __('Video size question display time') }}" value="" required>
                                        @if ($errors->has('question_display_time'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('question_display_time') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6 form-group{{ $errors->has('answer_display_time') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="name">{{ __('Answer Display Time') }}</label>
                                        <input type="number" name="answer_display_time" id="answer_display_time" class="form-control form-control-alternative{{ $errors->has('answer_display_time') ? ' is-invalid' : '' }}" placeholder="{{ __('Video size answer display time') }}" value="" required>
                                        @if ($errors->has('answer_display_time'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('answer_display_time') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6 form-group">
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

