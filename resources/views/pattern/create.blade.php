@extends('layouts.app', ['title' => __('Pattern Management')])

@section('content')
    @include('pattern.partials.header', ['title' => __('Add Pattern')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Pattern Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('pattern.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">{{ __('Pattern information') }}</h6>
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('pattern.store') }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                <!-- </div> -->
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-md-6 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="name">{{ __('Name') }}</label>
                                            <input type="text" name="name" id="name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Pattern name') }}" value="" required>
                                            @if ($errors->has('name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('template_type') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="type">{{ __('Template Type') }}</label>
                                            <select name="template_type" id="template_type" class="form-control form-control-alternative{{ $errors->has('template_type') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                            @foreach($typeList as $res)
                                                <option value="{{$res->id}}">{{$res->type}}</option>
                                            @endforeach
                                            
                                            @if ($errors->has('template_type'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('template_type') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('ratio') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="type">{{ __('Ratio') }}</label>
                                            <select name="ratio" id="ratio" class="form-control form-control-alternative{{ $errors->has('ratio') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                            @foreach($ratioList as $res)
                                                <option value="{{$res->id}}">{{$res->name}}</option>
                                            @endforeach
                                            
                                            @if ($errors->has('ratio'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('ratio') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('type') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="parent_id">{{ __('Image Type') }}</label>
                                            <select name="image_type" id="image_type" class="form-control form-control-alternative{{ $errors->has('image_type') ? ' is-invalid' : '' }}" value="{{old('type')}}" required>
                                                <option value=""> -- </option>
                                                <option value="with_image">With Image</option>
                                                <option value="without_image">Without Image</option>
                                            
                                            @if ($errors->has('image_type'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('image_type') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('video_type') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="video_type">{{ __('Video Type') }}</label>
                                            <select name="video_type" id="video_type" class="form-control form-control-alternative{{ $errors->has('video_type') ? ' is-invalid' : '' }}" required>
                                            <option value=""> -- </option>
                                            @foreach($videoList as $res)
                                                <option value="{{$res->id}}">{{$res->name}}</option>
                                            @endforeach
                                            
                                            @if ($errors->has('video_type'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('video_type') }}</strong>
                                                </span>
                                            @endif
                                      
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('image') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="image">{{ __('Image') }}</label>

                                            <input type="file" name="image" id="image" class="form-control form-control-alternative{{ $errors->has('image') ? ' is-invalid' : '' }}" value="">

                                            @if ($errors->has('image'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('image') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                     
                                       

                                       
                                       
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
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
