@extends('layouts.app', ['title' => __('Secondary Language')])

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
                                <h3 class="mb-0">{{ __('Secondary Language') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('secondary_language.create') }}" class="btn btn-sm btn-primary">{{ __('Add Secondary Language') }}</a>
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
                                    <th scope="col">{{ __('ID') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Primary Language') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col" class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach ($languages as $key=> $secondary_language)
                                    <tr>
                                        <td><a href="{{ route('secondary_language.edit', $secondary_language) }}">{{ $key+1 }}</a></td>
                                        <td>{{ $secondary_language->name }}</td>
                                        <td>
                                            <?php
                                                $language = App\Languages::where('id', '=', $secondary_language->parent_id)->first();
                                                //echo "<pre>";print_r($languages->name);die;
                                                
												if($language!=null)
												{
													echo $language['name'];
												}
												else{
													echo '-';
												}
                                            ?>
                                        </td>
                                       
                                        @if($secondary_language->status==1)
                                         <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('secondaryLanguageStatus','{{$secondary_language->id}}','0')" checked >
                                                <div class="slider round"></div>
                                            </label>
                                        </td>
                                        @else
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('secondaryLanguageStatus','{{$secondary_language->id}}','1')" >
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
                                                   
                                                    <a class="dropdown-item" href="{{ route('secondary_language.edit', $secondary_language) }}">{{ __('Edit') }}</a>
                                                    <form action="{{ route('secondary_language.destroy', $secondary_language) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this Sub category?") }}') ? this.parentElement.submit() : ''">
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
                        @if(count($languages))
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $languages->appends(Request::all())->links() }}
                            </nav>
                        @else
                            <h4>{{ __('You don`t have any secondary language') }} ...</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection
