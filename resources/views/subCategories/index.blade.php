@extends('layouts.app', ['title' => __('Sub Category')])

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
                                <h3 class="mb-0">{{ __('Sub Categories') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('subCategories.create') }}" class="btn btn-sm btn-primary">{{ __('Add sub category') }}</a>
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
                        <table class="table align-items-center table-flush" id="readyvidsdata1">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('ID') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Parent Category') }}</th>
                                    <th scope="col">{{ __('Video Type') }}</th>
                                    <th scope="col">{{ __('Icon') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col" class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach ($categories as $key=> $subCategories)
                                    <tr>
                                        <td><a href="{{ route('subCategories.edit', $subCategories) }}">{{ $key+1 }}</a></td>
                                        <td>{{ $subCategories->name }}</td>
                                        <td>
                                            <?php
                                                $parentcategory = DB::table("categories")->select('name')->where('id', '=', $subCategories->parent_id)->first();
												if($parentcategory)
												{
													echo $parentcategory->name;
												}
												else{
													echo '-';
												}
                                            ?>
                                        </td>
                                         <td>
                                            <?php
                                                $video_type = DB::table("section")->select('name')->where('id', '=', $subCategories->video_type)->first();
												if($video_type)
												{
													echo $video_type->name;
												}
												else{
													echo '-';
												}
                                            ?>
                                        </td>
                                        <td>
                                            <img width="50px" height="50px" src="{{ asset("uploads/category/{$subCategories->cat_icon}") }}">
                                        </td>
                                        @if($subCategories->status==1)
                                         <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('subcategoryStatus','{{$subCategories->id}}','0')" checked >
                                                <div class="slider round"></div>
                                            </label>
                                        </td>
                                        @else
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('subcategoryStatus','{{$subCategories->id}}','1')" >
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
                                                    <a class="dropdown-item" href="{{ route('subCategories.clone', $subCategories) }}">{{ __('Clone') }}</a>
                                                    <a class="dropdown-item" href="{{ route('subCategories.edit', $subCategories) }}">{{ __('Edit') }}</a>
                                                    <form action="{{ route('subCategories.destroy', $subCategories) }}" method="post">
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
                        @if(count($categories))
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $categories->appends(Request::all())->links() }}
                            </nav>
                        @else
                            <h4>{{ __('You don`t have any sub category') }} ...</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection
