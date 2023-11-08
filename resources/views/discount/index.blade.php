@extends('layouts.app', ['title' => __('Discount')])

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
                                <h3 class="mb-0">{{ __('Discount') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('discount.create') }}" class="btn btn-sm btn-primary">{{ __('Add Discount') }}</a>
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
                                    <th scope="col">{{ __('Image') }}</th>
                                    <th scope="col">{{ __('Discount Type') }}</th>
                                    <th scope="col">{{ __('Price') }}</th>
                                    <th scope="col">{{ __('Copoun Code') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col" class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($discountlist as $key => $discounts)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{ ucfirst($discounts->name) }}</td>
                                        <td>
                                            <img width="50px" height="50px" src="{{ asset("{$discounts->icon}") }}">
                                        </td>
                                       
                                        <td>{{ ucfirst($discounts->discount_type) }}</td>
                                        <td>{{ ucfirst($discounts->price) }}</td>
                                        <td>{{ ucfirst($discounts->code) }}</td>
                                        @if($discounts->status==1)
                                         <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('discountStatus','{{$discounts->id}}','0')" checked >
                                                <div class="slider round"></div>
                                            </label>
                                        </td>
                                        @else
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('discountStatus','{{$discounts->id}}','1')" >
                                                <div class="slider round"></div>
                                            </label>
                                        </td>
                                        @endif
                                     
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                   
                                                    <a class="dropdown-item" href="{{ route('discount.edit', $discounts) }}">{{ __('Edit') }}</a>
                                                    <form action="{{ route('discount.destroy', $discounts) }}" method="post">
                                                        @csrf
                                                        @method('delete')

                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this Discount?") }}') ? this.parentElement.submit() : ''">
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
                            {{ $discountlist->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
