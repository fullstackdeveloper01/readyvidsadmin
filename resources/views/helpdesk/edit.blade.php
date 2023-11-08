@extends('layouts.app', ['name' => __('Image Management')])

@section('content')
    @include('image.partials.header', ['name' => __('Edit Image')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Image Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('image.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">{{ __('Image information') }}</h6>
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('image.update', $image) }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                </div>
                                <div class="pl-lg-4">
                                    <div class="form-group{{ $errors->has('parent_id') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="parent_id">{{ __('Folder Name') }}</label>
                                        <select name="folder_id" id="folder_id" class="form-control form-control-alternative{{ $errors->has('folder_id') ? ' is-invalid' : '' }}" required>
                                            <option value=""> -- </option>
                                        @foreach($folders as $res)
                                            <option value="{{$res->id}}" @if($res->id==$image->folder_id) selected @endif>{{$res->folder_name}}</option>
                                        @endforeach
                                        
                                        @if ($errors->has('folder_id'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('folder_id') }}</strong>
                                            </span>
                                        @endif
                                        </select>
                                    </div>
                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="name">{{ __('Image Name') }}</label>
                                        <input type="text" name="name" id="name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Image name') }}" value="{{$image->name}}" required>
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                   
                                    <div class="form-group">
                                        <label class="form-control-label" for="name">{{ __('Image') }}</label>
                                        <input type="file" name="image_file" class="form-control">
                                        @if ($errors->has('image_file'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('image_file') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Selected Icon</label>
                                        <img width="100px" height="100px" src="{{ asset($image->relative_image_path) }}">
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success mt-4">{{ __('Update') }}</button>
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

