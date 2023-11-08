@extends('layouts.app', ['title' => __('Gallery')])

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
                                <h3 class="mb-0">{{ __('Gallery') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('gallery.create') }}" class="btn btn-sm btn-primary">{{ __('Add Gallery') }}</a>
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
                                    <th scope="col">{{ __('Type') }}</th>
                                    <th scope="col">{{ __('Photo/Video') }}</th>
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Description') }}</th>
                                    <th scope="col" class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($galleries as $gallery)
                                    <tr>
                                        <td><a href="#{{--route('gallery.edit', $gallery)--}}">{{ $gallery->image_type }}</a></td>
                                        @if($gallery->image_type == 'Photo')
                                            <td>
                                                <img width="50px" alt="{{ $gallery->photo_video }}" height="50px" src="{{ asset("uploads/gallery/{$gallery->photo_video}") }}">
                                            </td>
                                        @else
                                            <td>
                                                <img width="50px" alt="{{ $gallery->photo_video }}" height="50px" src="{{ asset("uploads/gallery/{$gallery->photo_video}") }}">
                                            </td>
                                            <!-- <td>{{--$gallery->photo_video--}}</td> -->
                                        @endif
                                        <td>{{ $gallery->title }}</td>
                                        <td>{{ $gallery->description }}</td>
                                        <td class="text-right">
                                            <form action="{{ route('gallery.destroy', $gallery) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="btn btn-danger" onclick="confirm('{{ __("Are you sure you want to delete this?") }}') ? this.parentElement.submit() : ''">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        @if(count($galleries))
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $galleries->appends(Request::all())->links() }}
                            </nav>
                        @else
                            <h4>{{ __('You don`t have any gallery') }} ...</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
