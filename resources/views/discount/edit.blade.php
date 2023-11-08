@extends('layouts.app', ['title' => __('Discount Management')])

@section('content')
    @include('city.partials.header', ['title' => __('Edit Discount')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Discount Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('discount.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('discount.update', $discount) }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <hr />
                                <h6 class="heading-small text-muted mb-4">{{ __('discount information') }}</h6>
                                <div class="pl-lg-4">
                                    <div class="col-md-6 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">

                                    <label class="form-control-label" for="name">{{ __('Name') }}</label>

                                    <input type="text" name="name" id="name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', $discount->name) }}">
                             
                                    @if ($errors->has('name'))

                                        <span class="invalid-feedback" role="alert">

                                            <strong>{{ $errors->first('name') }}</strong>

                                        </span>

                                    @endif

                                    </div>
                                         <div class="col-md-6 form-group{{ $errors->has('description') ? ' has-danger' : '' }}">

                                        <label class="form-control-label" for="Discount_name">{{ __('Description') }}</label>

                                        <textarea name="description" id="description" class="form-control form-control-alternative{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Discount Name') }}">{{$discount->description}}</textarea>                                      
                                        @if ($errors->has('description'))

                                            <span class="invalid-feedback" role="alert">

                                                <strong>{{ $errors->first('description') }}</strong>

                                            </span>

                                        @endif
                                        

                                    </div>
                                     <div class="col-md-6 form-group{{ $errors->has('code') ? ' has-danger' : '' }}">

                                        <label class="form-control-label" for="code">{{ __('Code') }}</label>

                                        <input type="text" name="code" id="code" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="{{ __('Code') }}" value="{{$discount->code}}">
                                      
                                        @if ($errors->has('code'))

                                            <span class="invalid-feedback" role="alert">

                                                <strong>{{ $errors->first('code') }}</strong>

                                            </span>

                                        @endif

                                    </div>
                                    <div class="col-md-6 form-group{{ $errors->has('discount_type') ? ' has-danger' : '' }}">

                                        <label class="form-control-label" for="discount_type">{{ __('Discount Type') }}</label>

                                        <select name="discount_type" id="discount_type-create-form" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}">
                                            <option value="">Please select</option>
                                            <option value="fixed" @if($discount->discount_type=="fixed") selected @endif>Fixed</option>
                                            <option value="percentage"  @if($discount->discount_type=="percentage") selected @endif>Percentage</option>
                                        </select>
                                      
                                        @if ($errors->has('code'))

                                            <span class="invalid-feedback" role="alert">

                                                <strong>{{ $errors->first('code') }}</strong>

                                            </span>

                                        @endif

                                    </div>
                                    <div class="col-md-6 form-group{{ $errors->has('price') ? ' has-danger' : '' }}">

                                        <label class="form-control-label" for="price">{{ __('price') }}</label>

                                        <input type="text" name="price" id="price" class="form-control form-control-alternative{{ $errors->has('price') ? ' is-invalid' : '' }}" placeholder="{{ __('Price') }}" value="{{$discount->price}}">
                                      
                                        @if ($errors->has('price'))

                                            <span class="invalid-feedback" role="alert">

                                                <strong>{{ $errors->first('price') }}</strong>

                                            </span>

                                        @endif

                                    </div>
                                    <div class="col-md-6 form-group">
                                    <label>Icon</label>
                                    <input type="file" name="icon" class="form-control">
                                    </div>
                                    <div class="col-md-6  form-group">
                                        <label>Selected Icon</label>
                                        <img width="100px" height="100px" src="{{ asset("{$discount->icon}") }}">
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success mt-4">{{ __('Update') }}</button>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
