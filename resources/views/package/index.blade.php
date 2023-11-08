@extends('layouts.app', ['title' => __('Packages')])

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
                                <h3 class="mb-0">{{ __('Package List') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('package.create') }}" class="btn btn-sm btn-primary">{{ __('Add Package') }}</a>
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
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Short Video') }}</th>
                                    <th scope="col">{{ __('Long Video') }}</th>
                                    <th scope="col">{{ __('Price') }}</th>
                                    <!--<th scope="col">{{ __('Hours') }}</th>-->
                                    <!-- <th scope="col">{{ __('Color') }}</th> -->
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col" class="text-right">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($packages as $key => $package)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $package->package_title }}</td>
                                        <td><span class="editable">{!! $package->short_video !!}</span></td>
                                        <td><span class="editable">{{ $package->long_video }}</span></td>
                                        <td>{{ $package->package_price }}</td>
                                        <!--<td>{{ $package->package_hours }}</td>-->
                                        <!-- <td>{{ $package->package_color }}</td> -->
                                        @if($package->active==1)
                                         <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('packageStatus','{{$package->id}}','0')" checked >
                                                <div class="slider round"></div>
                                            </label>
                                        </td>
                                        @else
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('packageStatus','{{$package->id}}','1')" >
                                                <div class="slider round"></div>
                                            </label>
                                        </td>
                                        @endif
                                        <td class="text-right d-flex align-items-center">
                                        
                                            <!-- <a class="btn btn-primary btn-sm" href="{{ route('package.edit', $package) }}">{{ __('Edit') }}</a>
                                            <form action="{{ route('package.destroy', $package) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                @if($package->active=='1')
                                            

                                                <button type="button" class="btn btn-danger btn-sm" onclick="confirm('{{ __("Are you sure you want to Deactive this package?") }}') ? this.parentElement.submit() : ''">
                                                    {{ __('Deactivate') }}
                                                </button>
                                                @else
                                                <button type="button" class="btn btn-success btn-sm" onclick="confirm('{{ __("Are you sure you want to Active this package?") }}') ? this.parentElement.submit() : ''">
                                                    {{ __('Active') }}
                                                </button>
                                           
                                                 @endif
                                            </form> -->
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                   
                                                    <a class="dropdown-item" href="{{ route('package.edit', $package) }}">{{ __('Edit') }}</a>
                                                    <form action="{{ route('package.destroy', $package) }}" method="post">
                                                        @csrf
                                                        @method('delete')

                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this package?") }}') ? this.parentElement.submit() : ''">
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
                        @if(count($packages))
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $packages->appends(Request::all())->links() }}
                            </nav>
                        @else
                            <h4>{{ __('You don`t have any package') }} ...</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
