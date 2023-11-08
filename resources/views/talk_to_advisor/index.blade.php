@extends('layouts.app', ['title' => __('talk_to_advisor')])

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
                                <h3 class="mb-0">{{ __('Talk To Advisor List') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('talk_to_advisor.create') }}" class="btn btn-sm btn-primary">{{ __('Add Talk To Advisor') }}</a>
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
                                    <th scope="col">{{ __('Phone') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Created Date') }}</th>
                                    <th scope="col" class="">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($talk_to_advisors as $key=> $talk_to_advisor)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{ $talk_to_advisor->name }}</td>
                                        <td>{{ $talk_to_advisor->phone }}</td>
                                        </td>
                                        @if($talk_to_advisor->status==1)
                                         <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('talkToAdvisorStatus','{{$talk_to_advisor->id}}','0')" checked >
                                                <div class="slider round"></div>
                                            </label>
                                        </td>
                                        @else
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('talkToAdvisorStatus','{{$talk_to_advisor->id}}','1')" >
                                                <div class="slider round"></div>
                                            </label>
                                        </td>
                                        @endif
                                        
                                        <td>{{ $talk_to_advisor->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                   
                                                    <a class="dropdown-item" href="{{ route('talk_to_advisor.edit', $talk_to_advisor) }}">{{ __('Edit') }}</a>
                                                    <form action="{{ route('talk_to_advisor.destroy', $talk_to_advisor) }}" method="post">
                                                        @csrf
                                                        @method('delete')

                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this talk_to_advisor?") }}') ? this.parentElement.submit() : ''">
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
                        @if(count($talk_to_advisors))
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $talk_to_advisors->appends(Request::all())->links() }}
                            </nav>
                        @else
                            <h4>{{ __('You don`t have any talk_to_advisor') }} ...</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
     
        @include('layouts.footers.auth')
    </div>
@endsection
