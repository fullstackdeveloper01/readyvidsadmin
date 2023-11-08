@extends('layouts.app', ['title' => __('Voice Bulk Upload Management')])

@section('content')
    @include('voice.partials.header', ['title' => __('Voice Bulk Upload')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Voice Bulk Upload Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('question_answer_voice.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">{{ __('Voice Bulk Upload information') }}</h6>
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('question_answer_voice.bulk_upload') }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                <!-- </div> -->
                                <div class="pl-lg-4">
                                    <div class="col-md-6 form-group{{ $errors->has('parent_id') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="parent_id">{{ __('Folder Name') }}</label>
                                        <select name="folder_id" id="folder_id" class="form-control form-control-alternative{{ $errors->has('folder_id') ? ' is-invalid' : '' }}" required>
                                            <option value=""> -- </option>
                                        @foreach($folders as $res)
                                            <option value="{{$res->id}}">{{$res->folder_name}}</option>
                                        @endforeach
                                        
                                        @if ($errors->has('folder_id'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('folder_id') }}</strong>
                                            </span>
                                        @endif
                                        </select>
                                    </div>
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
