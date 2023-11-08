@extends('layouts.app', ['name' => __('Sub Category Management')])

@section('content')
    @include('subCategories.partials.header', ['name' => __('Add Category')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Sub Category Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('subCategories.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">{{ __('Sub Category information') }}</h6>
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('subCategories.store') }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                </div>
                                <div class="pl-lg-4">
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
                                    <div class="col-md-6 form-group{{ $errors->has('parent_id') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="parent_id">{{ __('Category Name') }}</label>
                                        <select name="parent_id" id="parent_id" class="form-control form-control-alternative{{ $errors->has('parent_id') ? ' is-invalid' : '' }}" required>
                                            <option value=""> -- </option>
                                        @foreach($categoryList as $res)
                                            <option value="{{$res->id}}">{{$res->name}}</option>
                                        @endforeach
                                        
                                        @if ($errors->has('parent_id'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('parent_id') }}</strong>
                                            </span>
                                        @endif
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="name">{{ __('Name') }}</label>
                                        <input type="text" name="name" id="name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Sub Category name') }}" value="" required>
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                   
                                    <div class="col-md-6 form-group">
                                        <label>Icon</label>
                                        <input type="file" name="cat_icon" class="form-control" required>
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

