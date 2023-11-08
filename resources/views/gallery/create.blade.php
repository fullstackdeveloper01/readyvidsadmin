@extends('layouts.app', ['title' => __('Gallery Management')])

@section('content')
    @include('gallery.partials.header', ['title' => __('Add Gallery')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Gallery Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('gallery.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">{{ __('Gallery information') }}</h6>
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('gallery.store') }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                </div>
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="form-group col-4">
                                            <label>Type</label>
                                            <select name="image_type" id="image_type" class="form-control form-control-alternative{{ $errors->has('image_type') ? ' is-invalid' : '' }}" required onchange="setImageType(this.value)">
                                                <option value="Photo">Photo</option>
                                                <option value="Video">Video</option>                                      
                                            </select>
                                            @if ($errors->has('image_type'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('image_type') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-8 typephoto">
                                            <div class="typephoto">                                                
                                                <label>Photo</label>
                                                <input type="file" id="photo_video" name="photo_video" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group col-4">
                                            <div class="typethumb" style="display: none;">                                                
                                                <label>Thumbnail</label>
                                                <input type="file" id="thumbnail" name="thumbnail" class="form-control">
                                            </div>

                                        </div>
                                        <div class="form-group col-4">

                                            <div class="typeurl" style="display: none;">                                                
                                                <label>Youtube URL</label>
                                                <input type="url" id="photo_video_url" name="photo_video_url" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>Title</label>
                                            <input type="text" name="title" required=""  class="form-control" placeholder="Enter Title">
                                            @if ($errors->has('title'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('title') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-12">
                                            <div class="description">                                                
                                                <label>Desscription</label>
                                                <textarea class="form-control" name="description" placeholder="Enter Description"></textarea>
                                            </div>
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
    <script type="text/javascript">
        function setImageType(type)
        {
            if(type == 'Photo')
            {
                $('.typephoto').css('display','block');
                $('.typeurl').css('display','none');
                $('.typethumb').css('display','none');

                $('#photo_video').attr('required');
                $('#photo_video_url').removeAttr('required');
                $('#photo_video_thumb').removeAttr('required');

            }
            else
            {
                $('.typephoto').css('display','none');
                $('.typeurl').css('display','block');
                $('.typethumb').css('display','block');
                $('#photo_video_url').attr('required');
                $('#photo_video_thumb').attr('required');
                $('#photo_video').removeAttr('required');
            }
        }
    </script>
@endsection
