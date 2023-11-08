@extends('layouts.app', ['title' => __('User Management')])



@section('content')

    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">

    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">

    <div class="container-fluid mt--7">

        <div class="row">

            <div class="col">

                <div class="card shadow">

                    <div class="card-header border-0">

                        <div class="row align-items-center">

                            <div class="col-12">

                                <h3 class="mb-0">{{ __('Users List') }}</h3>
                                {{--  
                                <form method="get">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group{{ $errors->has('role') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="role">{{ __('Login Type') }}</label>
                                                <select name="role" id="role" class="form-control form-control-alternative{{ $errors->has('role') ? ' is-invalid' : '' }}" required>
                                                    <option value=""> -- </option>
                                                    <option value="4" @if(isset($_GET['role']) && $_GET['role']=="4") selected @endif>Affiliate User </option>
                                                    <option value="2" @if(isset($_GET['role']) && $_GET['role']=="2") selected @endif>User </option>
                                                @if ($errors->has('role'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('role') }}</strong>
                                                    </span>
                                                @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-8 text-right">   
                                            @php
                                            if(isset($_GET['role'])){
                                                $role=$_GET['role'];
                                            }else{
                                                $role='';
                                            }
                                          
                                            
                                            @endphp
                                          
                                            <button type="submit" class="btn btn-sm btn-primary">{{ __('Filter') }}</button>
                                            <a href="{{ route('user.index') }}" class="btn btn-sm btn-primary">{{ __('Clear Filter') }}</a>
                                        </div>
                                     
                                           
                                    </div>
                                        
                                   
                                  
                                   
                                </form>
                                --}}

                            </div>

                            {{--

                            <div class="col-4 text-right">

                                <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary">{{ __('Add user') }}</a>

                            </div>

                            --}}

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

                        <table class="table align-items-center table-flush" id="readyvidsdata">

                            <thead class="thead-light">

                                <tr>

                                    <th scope="col">{{ __('ID') }}</th>
                                      
                                    <th scope="col">{{ __('User Type') }}</th>
                                      
                                    <th scope="col">{{ __('Name') }}</th>

                                    <th scope="col">{{ __('Email') }}</th>

                                    <th scope="col">{{ __('Phone') }}</th>

                                    <th scope="col">{{ __('Subject') }}</th>
                                    
                                    <th scope="col">{{ __('Action') }}</th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($users as $user)

                                   
                                        <tr>

                                            <td><a class="btn badge badge-success badge-pill" href="javascript:void(0)">{{$user->id}}</a></td>
                                            
                                            <td>
                                                @if($user->role==4)
                                                Affilaite User
                                                @elseif($user->role==5)
                                                Business Partner
                                                @else
                                                User
                                                @endif
                                            </td>

                                            <td>{{$user->name}}</td>

                                            <td>

                                                <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>

                                            </td>
                                            <td>{{$user->phone}}</td>
                                           

                                            <td>
                                            {{$user->subject}}

                                            </td>
                                            <td class="text-right">
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                       
                                                        <a class="dropdown-item" onclick="view('{{$user->user_id}}','{{$user->id}}','{{$user->role}}','{{$user->message}}','{{asset($user->attachment)}}')" href="javascript:void(0)" data-toggle="modal" data-target="#ViewModal" class="btn btn-sm btn-primary">{{ __('View') }}</a>
                                                        <a class="dropdown-item" onclick="reply('{{$user->user_id}}','{{$user->id}}','{{$user->role}}','{{$user->subject}}')" href="javascript:void(0)" data-toggle="modal" data-target="#replyModal" class="btn btn-sm btn-primary">{{ __('Reply') }}</a>
                                                        <form action="{{ route('helpdesk.destroy', $user) }}" method="post">
                                                            @csrf
                                                            @method('delete')
    
                                                            <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this country?") }}') ? this.parentElement.submit() : ''">
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

                    

                </div>

            </div>

        </div>
        @include('helpdesk.partials.modals')
        @include('layouts.footers.auth')

    </div>

@endsection
@section('js')
<script>
function reply(userid,msgid,roleid,subject){
    $("#user_id").val(userid);
    $("#role_id").val(roleid);
    $("#message_id").val(msgid);
     $("#subject").val(subject);
    
        
   }
   function view(userid,msgid,roleid,message,attach){
      
    $("#user_id").val(userid);
    $("#role_id").val(roleid);
    $("#message").text(message);
     $("#attachment_id").attr('href',attach);
    
      
   }
</script>
@endsection