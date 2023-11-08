@extends('layouts.app', ['title' => __('Language')])

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
                                <h3 class="mb-0">{{ __('Language List') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('language.create') }}" class="btn btn-sm btn-primary">{{ __('Add Language') }}</a>
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
                                    <th scope="col">{{ __("Language") }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Created Date') }}</th>
                                    <th scope="col" class="">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($languages as $key=> $language)
                                    <tr>
                                    <td>{{$key+1}}</td>
                                        <td>{{$language->name}}</td>
                                        @if($language->status==1)
                                         <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('languageStatus','{{$language->id}}','0')" checked >
                                                <div class="slider round"></div>
                                            </label>
                                        </td>
                                        <!-- <td>Active</td> -->
                                        @else
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('languageStatus','{{$language->id}}','1')" >
                                                <div class="slider round"></div>
                                            </label>
                                        </td>
                                        <!-- <td>Inactive</td> -->
                                        @endif
                                        <td>{{ $language->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-right d-flex align-items-center">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <!-- <a class="btn btn-warning btn-sm" href="{{ route('language.edit', $language) }}">{{ __('Edit') }}</a> -->
                                                    <a class="dropdown-item" href="{{ route('language.edit', $language) }}">{{ __('Edit') }}</a>
                                                    <form action="{{ route('language.destroy', $language) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        
                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this language?") }}') ? this.parentElement.submit() : ''">
                                                            {{ __('Delete') }}
                                                        </button>
                                                        
                                                        <!-- @if($language->active==1)
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirm('{{ __("Are you sure you want to deactivate this language?") }}') ? this.parentElement.submit() : ''">
                                                            {{ __('Inactivate') }}
                                                        </button>
                                                        @else
                                                        <button type="button" class="btn btn-success btn-sm" onclick="confirm('{{ __("Are you sure you want to activate this language?") }}') ? this.parentElement.submit() : ''">
                                                            {{ __('Activate') }}
                                                        </button>
                                                        @endif -->
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
                        @if(count($languages))
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $languages->appends(Request::all())->links() }}
                            </nav>
                        @else
                            <h4>{{ __('You don`t have any language') }} ...</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection

<script>

</script>