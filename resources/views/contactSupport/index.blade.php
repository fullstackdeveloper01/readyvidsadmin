@extends('layouts.app', ['title' => __('Contact Support')])

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
                                <h3 class="mb-0">{{ __('Support Query List') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <!-- <a href="{{ route('girls.create') }}" class="btn btn-sm btn-primary">{{ __('Add Girls') }}</a> -->
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
                                    <th scope="col">{{ __('Full Name') }}</th>
                                    <th scope="col">{{ __('Topic') }}</th>
                                    <th scope="col">{{ __('Image') }}</th>
                                    <th scope="col">{{ __('Description') }}</th>
                                    <th scope="col">{{ __('Reply') }}</th>
                                    <th scope="col">{{ __('Created Date') }}</th>
                                    <th scope="col" class="">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($response as $key=> $support)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{ App\Helpers\Helper::userName($support->user_id) }}</td>
                                        <td>{{ $support->topic }}</td>
                                        <td> <img src="{{url('/'.$support->image)}}" onerror="this.onerror=null;this.style.display='none';"  width="100" height="100"> </td>
                                        <td> {{ $support->description }} </td>
                                            @if($support->reply==1)
                                        <td style="color:green;">
                                                Replied
                                        </td>
                                            @else
                                        <td style="color:red;">
                                                Not Replied
                                        </td>
                                            @endif
                                        
                                        <td>{{ $support->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-right v-align-text-bottom">
                                            <div class="d-flex align-items-center ">
                                                @if($support->reply!=1)
                                                <button type="button" class="btn btn-primary btn-sm modale" data-toggle="modal" data-target="#replyModal" data-id="{{ $support->id }}" data-userid="{{ $support->user_id }}" data-description="{{ $support->description }}" data-topic="{{$support->topic}}">Reply </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        <nav class="d-flex justify-content-end" aria-label="...">
                            {{ $response->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).on('click','.modale',function(){
                var event = $(this);
                let id = event.attr('data-id');
                let userid = event.attr('data-userid');
                let data = event.attr('data-description');
                let topic = event.attr('data-topic');
                $('.query').text(data);
                 $('.topic').text(topic);
                $('input[name="id"]').val(id);
                $('input[name="userid"]').val(userid);
                // $.ajax({
                //     url: 'toprated/'+id+'/'+data,
                //     type: 'get',
                //     dataType: 'json',
                //     success: function(response){
                //         if(response.status == true){
                //             if (data == 1) {
                //                 event.attr('data-data','0');
                //                 event.addClass('btn-danger');
                //                 event.removeClass('btn-success');
                //                 event.children('i').addClass('fa-toggle-off')
                //                 event.children('i').removeClass('fa-toggle-on')
                //             }else{
                //                 event.attr('data-data','1');
                //                 event.addClass('btn-success');
                //                 event.removeClass('btn-danger');
                //                 event.children('i').addClass('fa-toggle-on')
                //                 event.children('i').removeClass('fa-toggle-off')
                //             }
                //         }
                //     }
                // }); 
            })
            
        </script>
        @include('layouts.footers.auth') 
        @include('contactSupport.partials.modals')
    </div>
@endsection
