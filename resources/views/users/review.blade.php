@extends('layouts.app', ['title' => __('Review Management')])

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
                                <h3 class="mb-0">{{ __('Review Management') }}</h3>
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
                        <table class="table align-items-center table-flush shyamtrusttable">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('S.No') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Email') }}</th>
                                    <th scope="col">{{ __('Review') }}</th>
                                    <th scope="col">{{ __('Rating') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rating as $key=> $value)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{ $value->name }}</td>
                                            <td>
                                                <a href="mailto:{{ $value->email }}">{{ $value->email }}</a>
                                            </td>
                                            <td>{{ $value->review }}</td>
                                            <td>{{ $value->rating }}</td>
                                            <td>
                                                @if($value->staus==1)
                                                    Active
                                                @else
                                                    Inactive
                                                @endif
                                            </td>
                                            <td>
                                                @if($value->status==0)
                                                    <button type="button" onclick="changereviewStatus('{{$value->id}}')" class="btn btn-sm btn-toggle valueStatus" data-toggle="button" aria-pressed="true" autocomplete="off">
                                                        <div class="handle"></div>
                                                    </button>
                                                @else
                                                    <button type="button" onclick="changereviewStatus('{{$value->id}}')" class="btn btn-sm btn-toggle active valueStatus" data-toggle="button" aria-pressed="true" autocomplete="off">
                                                        <div class="handle"></div>
                                                    </button>
                                                @endif
                                            </td>
                                            
                                        </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection















