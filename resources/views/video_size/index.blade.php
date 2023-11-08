@extends('layouts.app', ['title' => __('Video Size')])

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Video Size List') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('video_size.create') }}" class="btn btn-sm btn-primary">{{ __('Add Video Size') }}</a>
                            </div>
                        </div>
                    </div>

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

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('S.No') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Description') }}</th>
                                    <th scope="col">{{ __('Question Display Time') }}</th>
                                    <th scope="col">{{ __('Answer Display Time') }}</th> 
                                    <th scope="col">{{ __('Icon') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Created Date') }}</th>
                                    <th scope="col" class="">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($video_size as $key=> $videosize)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{ $videosize->name }}</td>
                                        <td>{{ $videosize->description }}</td>
                                        <td>{{ $videosize->question_display_time }}</td>
                                        <td>{{ $videosize->answer_display_time }}</td>
                                        <td>
                                            <img width="50px" height="50px" src="{{ asset("{$videosize->image}") }}">
                                        </td>
                                        </td>
                                        @if($videosize->status==1)
                                         <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('videosizeStatus','{{$videosize->id}}','0')" checked >
                                                <div class="slider round"></div>
                                            </label>
                                        </td>
                                        @else
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('videosizeStatus','{{$videosize->id}}','1')" >
                                                <div class="slider round"></div>
                                            </label>
                                        </td>
                                        @endif
                                        
                                        <td>{{ $videosize->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                   
                                                    <a class="dropdown-item" href="{{ route('video_size.edit', $videosize) }}">{{ __('Edit') }}</a>
                                                    <form action="{{ route('video_size.destroy', $videosize) }}" method="post">
                                                        @csrf
                                                        @method('delete')

                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this videosize?") }}') ? this.parentElement.submit() : ''">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        @if(count($video_size))
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $video_size->appends(Request::all())->links() }}
                            </nav>
                        @else
                            <h4>{{ __('You don`t have any videosize') }} ...</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).on('click','.toggle',function(){
                var event = $(this);
                let id = $(this).attr('data-id');
                let data = $(this).attr('data-data');
                $.ajax({
                    url: 'toprated/'+id+'/'+data,
                    type: 'get',
                    dataType: 'json',
                    success: function(response){
                        if(response.status == true){
                            if (data == 1) {
                                event.attr('data-data','0');
                                event.addClass('btn-danger');
                                event.removeClass('btn-success');
                                event.children('i').addClass('fa-toggle-off')
                                event.children('i').removeClass('fa-toggle-on')
                            }else{
                                event.attr('data-data','1');
                                event.addClass('btn-success');
                                event.removeClass('btn-danger');
                                event.children('i').addClass('fa-toggle-on')
                                event.children('i').removeClass('fa-toggle-off')
                            }
                        }
                    }
                }); 
            })
            
        </script>
        @include('layouts.footers.auth')
    </div>
@endsection
