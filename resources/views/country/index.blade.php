@extends('layouts.app', ['title' => __('Country')])

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
                                <h3 class="mb-0">{{ __('Country') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('country.create') }}" class="btn btn-sm btn-primary">{{ __('Add Country') }}</a>
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
                                    <th scope="col">{{ __('Country Name') }}</th>
                                    <th scope="col">{{ __('Country Flag') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col" class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($country as $key => $countrys)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{ ucfirst($countrys->country_name) }}</td>
                                        <td>
                                            <img width="50px" height="50px" src="{{ asset("{$countrys->icon}") }}">
                                        </td>
                                        @if($countrys->active==1)
                                         <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('countryStatus','{{$countrys->id}}','0')" checked >
                                                <div class="slider round"></div>
                                            </label>
                                        </td>
                                        @else
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('countryStatus','{{$countrys->id}}','1')" >
                                                <div class="slider round"></div>
                                            </label>
                                        </td>
                                        @endif
                                        <!-- <td>{{ $countrys->created_at->format('d/m/Y H:i') }}</td> -->
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                   
                                                    <a class="dropdown-item" href="{{ route('country.edit', $countrys) }}">{{ __('Edit') }}</a>
                                                    <form action="{{ route('country.destroy', $countrys) }}" method="post">
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
                    <div class="card-footer py-4">
                        <nav class="d-flex justify-content-end" aria-label="...">
                            {{ $country->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
