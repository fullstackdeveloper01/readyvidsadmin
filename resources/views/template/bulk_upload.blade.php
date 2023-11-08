@extends('layouts.app', ['title' => __('Template Management')])

@section('content')
    @include('template.partials.header', ['title' => __('Bulk Upload')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Template Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('template.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">{{ __('Template information') }}</h6>
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('template.bulk_upload') }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                <!-- </div> -->
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-md-6 form-group{{ $errors->has('bulkupload') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="bulkupload">{{ __('Bulk Upload') }}</label>
                                            <input type="file" name="bulkupload" id="bulkupload" class="form-control form-control-alternative{{ $errors->has('bulkupload') ? ' is-invalid' : '' }}"  required>
                                            @if ($errors->has('bulkupload'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('bulkupload') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                    </div>
                                  

                                  
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
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
