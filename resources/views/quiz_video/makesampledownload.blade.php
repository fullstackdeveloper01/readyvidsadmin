@extends('layouts.app', ['title' => __('Video Management')])

@section('content')
    @include('video.partials.header', ['title' => __('Make Sample Video')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Video Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('quiz_video.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">{{ __('Video information') }}</h6>
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('quiz_video.makesampledownload') }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                <!-- </div> -->
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-md-6 form-group{{ $errors->has('template_type_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="template_type_id">{{ __('Template Type') }}</label>
                                            <select name="template_type_id" id="template_type_id" class="form-control form-control-alternative{{ $errors->has('template_type_id') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                            @foreach($templateTypeList as $res)
                                                <option value="{{$res->id}}">{{$res->type}}</option>
                                            @endforeach
                                            
                                            @if ($errors->has('template_type_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('template_type_id') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('country_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="country_id">{{ __('Country') }}</label>
                                            <select name="country_id" id="country_id" class="form-control form-control-alternative{{ $errors->has('country_id') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                            @foreach($countryList as $res)
                                                <option value="{{$res->id}}">{{$res->country_name}}</option>
                                            @endforeach
                                            
                                            @if ($errors->has('country_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('country_id') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6 form-group{{ $errors->has('subject_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="subject_id">{{ __('Subject') }}</label>
                                            <select name="subject_id" id="subject_id" class="form-control form-control-alternative{{ $errors->has('subject_id') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                            @if ($errors->has('subject_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('subject_id') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('option_type_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="subcategory">{{ __('Option Type') }}</label>
                                            <select name="option_type_id" id="option_type_id" class="form-control form-control-alternative{{ $errors->has('option_type_id') ? ' is-invalid' : '' }}" value="old('option_type_id')" required>
                                                <option value=""> -- </option>
                                                @foreach($optionTypeList as $res)
                                                    <option value="{{$res->id}}">{{$res->type}}</option>
                                                @endforeach
                                                @if ($errors->has('option_type_id'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('option_type_id') }}</strong>
                                                    </span>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('topic_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="topic_id">{{ __('Topic') }}</label>
                                            <select name="topic_id" id="topic_id" class="form-control form-control-alternative{{ $errors->has('topic_id') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                           
                                            
                                            @if ($errors->has('topic_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('topic_id') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                    </div>
                                       
                                    
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success mt-4">{{ __('Download Sample') }}</button>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

    <script type="text/javascript">
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


            $('#option_type_id').change(function(){
                var country_id = $("#country_id").val();
                if(country_id==''){
                    country_id ='0';
                }
                
                var subject_id = $("#subject_id").val();
                   if(subject_id==''){
                    subject_id ='0';
                }
                var option_type_id = $("#option_type_id").val();
                   if(option_type_id==''){
                    option_type_id ='0';
                }
                 var base_url='<?php echo env('BASE_URL')?>';
              
                  $.ajax({

                    method: 'get',
        
                    url: base_url+'/topic_list/'+country_id+'/'+subject_id+'/'+option_type_id,
        
                }).then(response => {
                    if (response.status == true) {
        
                        var topics = response.data;
                        
                        if(topics.length>0){
                            var topichtml ='<option value=""></option>';
                             for(var counter=0;counter<topics.length;counter++){
                                topichtml +='<option value="'+topics[counter].id+'">'+topics[counter].name+'</option>';
                            }
                            $("#topic_id").html(topichtml);
                        }
                    } else {
        
                          $("#topic_id").html('<option value="">---------</option>');
        
                    
        
                    }
        
                    
        
                }).catch(function (error) {
        
                    console.log(error);
        
                });
            });
    </script>
@endsection
