@extends('layouts.app', ['title' => __('topic Management')])

@section('content')
    @include('categories.partials.header', ['title' => __('Clone Topic')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Topic Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('topics.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">                                
                        <form method="post" action="{{ route('topics.clone', $topic) }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                               
                            <hr />
                            <h6 class="heading-small text-muted mb-4">{{ __('Topic Information') }}</h6>
                            <div class="pl-lg-4">
                               
                                <div class="col-md-6 form-group{{ $errors->has('country_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="country_id">{{ __('Country Name') }}</label>
                                    <select name="country_id" id="country_id" class="form-control form-control-alternative{{ $errors->has('country_id') ? ' is-invalid' : '' }}" required>
                                        <option value=""> -- </option>
                                    @foreach($countryList as $res)
                                        <option value="{{$res->id}}" @if($res->id==$topic->country_id) selected @endif>{{$res->country_name}}</option>
                                    @endforeach
                                    
                                    @if ($errors->has('country_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('country_id') }}</strong>
                                        </span>
                                    @endif
                                    </select>
                                </div>
                                <div class="col-md-6 form-group{{ $errors->has('subject_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="subject_id">{{ __('Subject Name') }}</label>
                                    <select name="subject_id" id="subject_id" class="form-control form-control-alternative{{ $errors->has('subject_id') ? ' is-invalid' : '' }}" required>
                                        <option value=""> -- </option>
                                    
                                        @foreach($subjectList as $res)
                                            <option value="{{$res->id}}" @if($res->id==$topic->subject_id) selected @endif>{{$res->name}}</option>
                                        @endforeach
                                    @if ($errors->has('subject_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('subject_id') }}</strong>
                                        </span>
                                    @endif
                                    </select>
                                </div>
                                <div class="col-md-6 form-group{{ $errors->has('option_type_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="option_type_id">{{ __('Option Type') }}</label>
                                    <select name="option_type_id" id="option_type_id" class="form-control form-control-alternative{{ $errors->has('option_type_id') ? ' is-invalid' : '' }}" required>
                                        <option value=""> -- </option>
                                    @foreach($optionTypeList as $res)
                                        <option value="{{$res->id}}" @if($res->id==$topic->option_type_id) selected @endif>{{$res->type}}</option>
                                    @endforeach
                                    
                                    @if ($errors->has('option_type_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('option_type_id') }}</strong>
                                        </span>
                                    @endif
                                    </select>
                                </div>
                                <div class="col-md-6 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative" placeholder="{{ __('topic name') }}" value="{{ old('name', $topic->name) }}">
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Icon</label>
                                    <input type="file" name="icon" class="form-control">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Selected Icon</label>
                                    <img width="100px" height="100px" src="{{ asset("{$topic->icon}") }}">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Clone') }}</button>
                                </div>                                
                            </div>    
                        </from>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
@section('js')
<script>
    
    $("#country_id").change(function () {
        var id = $('#country_id').val();
        var base_url='<?php echo env('BASE_URL')?>';
        $.ajax({

            url: base_url+'subject_list/'+id,

            type: 'get',

            dataType: 'json',

            success: function(response){

                console.log(response);

                if(response)

                {
                    var result = response.data;
                    var html='<option>----</option>';
                    for(var i=0;i<result.length;i++){
                        html += '<option value="'+result[i].id+'">'+result[i].name+'</option>'
                    }
                    $("#subject_id").html(html);
                    //alert('Status update successfully');

                }else{

                    alert('Access denied');

                }

            }

        });
    });


</script>
@endsection
