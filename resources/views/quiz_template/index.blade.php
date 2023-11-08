@extends('layouts.app', ['title' => __('Templates')])

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
                                <h3 class="mb-0">{{ __('Template List') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('quiz_template.create') }}" class="btn btn-sm btn-primary">{{ __('Add template') }}</a>
                                <a href="{{ route('quiz_template.makesampledownload') }}" class="btn btn-sm btn-primary">{{ __('Make Sample Download') }}</a>
                                 <a href="{{ route('quiz_template.bulk_upload') }}" class="btn btn-sm btn-primary">{{ __('Bulk Upload') }}</a>
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
                        @foreach($templates as $quiz_templates)
                            @if(!empty($quiz_templates->template_image))
                                <div class="col-md-2 text-center border shadow">
                                    <img src="{{ asset($quiz_templates->image)}}" class="img-fluid w-100"  data-toggle="modal" data-target="#imageModal" onclick="showImage('{{$quiz_templates->template_image}}','{{$quiz_templates->template_name}}')">
                                    <h4 class="mt-2">{{$quiz_templates->template_name}}</h4>
                                  
                                     <form action="{{route('quiz_template.destroy',$quiz_templates)}}" method="post">
                                        @csrf
                                        @method('delete')

                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this template?") }}') ? this.parentElement.submit() : ''">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                          
                            
                        @endforeach
                        </div>
                    </div>
                    <div class="card-footer py-4">
                        @if(count($templates))
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $templates->appends(Request::all())->links() }}
                            </nav>
                        @else
                            <h4>{{ __('You don`t have any template') }} ...</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
          @include('template.partials.modals')
        @include('layouts.footers.auth')
    </div>
    <script>
    function showImage(obj,name){
       // $('.modal-body').html(obj);
       $('#imagetemp').attr('src',obj);
        $('.modal-title').html(name);
    }
    </script>
@endsection
