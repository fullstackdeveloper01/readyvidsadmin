@extends('layouts.app', ['title' => __('Video Type Management')])

@section('content')
    @include('section.partials.header', ['title' => __('Edit Video Type')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Video Type Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('section.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">                                
                        <form method="post" action="{{ route('section.update', $section) }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                            <hr />
                            <h6 class="heading-small text-muted mb-4">{{ __('Video Type information') }}</h6>
                            <input type="hidden" name="id" value="{{$section->id}}">
                            <div class="pl-lg-4">
                                <div class="col-md-6 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="name">{{ __('Type') }}</label>
                                    <input type="text" name="name" id="name" class="form-control form-control-alternative{{ $errors->has('phrases') ? ' is-invalid' : '' }}" placeholder="{{ __('Type') }}" value="{{$section->name}}" required>
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                  <div class="col-md-6 form-group{{ $errors->has('image') ? ' has-danger' : '' }}">

                                        <label class="form-control-label" for="image">{{ __('Image') }}</label>

                                        <input type="file" name="image" id="image" class="form-control form-control-alternative{{ $errors->has('image') ? ' is-invalid' : '' }}">

                                        @if ($errors->has('image'))

                                            <span class="invalid-feedback" role="alert">

                                                <strong>{{ $errors->first('image') }}</strong>

                                            </span>

                                        @endif

                                    </div> 
                                    <div class="form-group">
                                        <label>Selected Image</label>
                                        <img src="{{ asset($section->icon) }}" type="image/*" height="50" width="50">
                                        
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
