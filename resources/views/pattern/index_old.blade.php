@extends('layouts.app', ['title' => __('Pattern')])

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h3 class="mb-0">{{ __('Pattern') }}</h3>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('pattern.create') }}" class="btn btn-sm btn-primary">{{ __('Add Pattern') }}</a>
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
                                    <th scope="col">{{ __('Id') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Template Type') }}</th>
                                    <th scope="col">{{ __('Ratio') }}</th>
                                    <th scope="col">{{ __('Image Type') }}</th>
                                    <th scope="col">{{ __('Image') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col" class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($patterns  as $key=> $pattern)
                                  
                                    <tr>
                                        <td><a href="{{ route('pattern.edit', $pattern) }}">{{ $key+1 }}</a></td>
                                        <td>{{$pattern->name}}</td>
                                        <td>{{$pattern->template_name}}</td>
                                        <td>{{$pattern->ratio_name}}</td>
                                        <td>{{$pattern->image_type}}</td>
                                        <td>
                                            <img width="50px" alt="{{ $pattern->image }}" height="50px" src="{{ asset($pattern->image) }}">
                                        </td>
                                       
                                        @if($pattern->status==1)
                                         <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('patternStatus','{{$pattern->id}}','0')" checked >
                                                <div class="slider round"></div>
                                            </label>
                                        </td>
                                        @else
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('patternStatus','{{$pattern->id}}','1')" >
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
                                                   
                                                    <a class="dropdown-item" href="{{ route('pattern.edit', $pattern) }}">{{ __('Edit') }}</a>
                                                    <form action="{{ route('pattern.destroy', $pattern) }}" method="post">
                                                        @csrf
                                                        @method('delete')

                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this pattern?") }}') ? this.parentElement.submit() : ''">
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
