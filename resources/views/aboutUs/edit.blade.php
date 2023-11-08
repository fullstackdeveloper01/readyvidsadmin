@extends('layouts.app', ['title' => __('About Us')])

@section('content')
    @include('users.partials.header', [
        'title' => __(''),
    ])

    <div class="container-fluid mt--7">
        <div class="row">            
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="col-12 mb-0">{{ __('About Us') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(isset($pages) && !empty($pages))
                        <form method="post" action="{{ route('aboutUs.update',$pages->id) }}" autocomplete="off">
                            @method('put')
                        @else
                        <form method="post" action="{{ route('aboutUs.store') }}" autocomplete="off">
                        @endif
                            @csrf

                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="pl-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label">{{ __('Description') }}</label>
                                    <textarea name="shyamtrusteditor">{{isset($pages) && $pages->content!=""?$pages->content:''}}</textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success btn-sm mt-4">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
