@extends('layouts.app', ['title' => __('Patterns')])

@section('content')
    <style>
        .editable{
            min-height: 300px!important;
            min-width: 300px!important;
            overflow-x: hidden!important;
            overflow-y: scroll!important;
            white-space: normal!important;
        }
    </style>
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Pattern List') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('quiz_pattern.create') }}" class="btn btn-sm btn-primary">{{ __('Add Pattern') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
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
                        <div class="row">
                        @foreach($patterns as $pattern)
                            
                            <div class="col-xl-3 col-lg-6">

                                <div class="card card-stats mb-4 mb-xl-0" style="min-height:250px;max-height:250px;">

                                    <div class="card-body" style="display: flex;align-items: center;justify-content: center;">
                                        <div class="pattern-box">
                                            <div class="row">

                                                <div class="col text-center">

                                                    @if($pattern->image)
                                                        <img src="{{asset("{$pattern->image}")}}" width="150px" height="150px">
                                                    @endif
                                                    <h5 class="card-title text-muted text-uppercase mb-0">  {{$pattern->template_name}}</h5>
                                                    <h5 class="card-title text-muted text-uppercase mb-0">  {{$pattern->ratio_name}}</h5>

                                                </div>

                                            </div>
                                            <div class="c-view-all text-center">
                                                <a href="{{ route('pattern.edit',$pattern) }}">{{ucfirst($pattern->name)}}</a>
                                            </div>

                                        </div>

                                      
                                    </div>

                                </div>  

                            </div>
                            
                        @endforeach
                        </div>
                    </div>
                    <div class="card-footer py-4">
                        @if(count($patterns))
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $patterns->appends(Request::all())->links() }}
                            </nav>
                        @else
                            <h4>{{ __('You don`t have any pattern') }} ...</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
