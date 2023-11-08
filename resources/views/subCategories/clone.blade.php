@extends('layouts.app', ['title' => __('category Management')])

@section('content')
    @include('categories.partials.header', ['title' => __('Clone Sub category')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Sub category Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('categories.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">                                
                        <form method="post" action="{{ route('subCategories.clone', $categories) }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                              
                            <hr />
                            <h6 class="heading-small text-muted mb-4">{{ __('Category information') }}</h6>
                            <div class="pl-lg-4">
                                <input type="hidden" name="id" value="{{$categories->id}}">
                                <div class="col-md-6 form-group{{ $errors->has('video_type') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="video_type">{{ __('Video Type') }}</label>
                                        <select name="video_type" id="video_type" class="form-control form-control-alternative{{ $errors->has('video_type') ? ' is-invalid' : '' }}" required>
                                        <option value=""> -- </option>
                                        @foreach($videoList as $res)
                                            <option value="{{$res->id}}" <?= ($categories->video_type == $res->id)?"selected":""; ?>>{{$res->name}}</option>
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
                                        <option value="{{$res->id}}" <?= ($categories->parent_id == $res->id)?"selected":""; ?>>{{$res->name}}</option>
                                    @endforeach
                                    
                                    @if ($errors->has('parent_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('parent_id') }}</strong>
                                        </span>
                                    @endif
                                    </select>
                                </div>
                                <div class="col-md-6 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative" placeholder="{{ __('categories name') }}" value="{{ old('name', $categories->name) }}">
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Icon</label>
                                    <input type="file" name="cat_icon" class="form-control">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Selected Icon</label>
                                    <img width="100px" height="100px" src="{{ asset("uploads/category/{$categories->cat_icon}") }}">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Clone') }}</button>
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
