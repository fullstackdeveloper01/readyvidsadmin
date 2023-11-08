@extends('layouts.app', ['title' => __('Quiz Video Management')])

@section('content')
    @include('video.partials.header', ['title' => __('Add Quiz Video')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Quiz Video Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('quiz_video.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">{{ __('Quiz Video information') }}</h6>
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('quiz_video.store') }}" autocomplete="off" enctype="multipart/form-data">
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
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 form-group{{ $errors->has('audio1') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio1">{{ __('Speaker1 Question Audio') }}</label>

                                            <input type="file" name="audio1" id="audio1" class="form-control form-control-alternative{{ $errors->has('audio1') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio1'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio1') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('answer_audio1') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="answer_audio1">{{ __('Speaker1 Answer Audio') }}</label>

                                            <input type="file" name="answer_audio1" id="answer_audio1" class="form-control form-control-alternative{{ $errors->has('answer_audio1') ? ' is-invalid' : '' }}" required>

                                            @if ($errors->has('answer_audio1'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('answer_audio1') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('audio2') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio2">{{ __('Speaker2 Question Audio') }}</label>

                                            <input type="file" name="audio2" id="audio2" class="form-control form-control-alternative{{ $errors->has('audio2') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('audio2'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio2') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('answer_audio2') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="answer_audio2">{{ __('Speaker2 Answer Audio') }}</label>

                                            <input type="file" name="answer_audio2" id="answer_audio2" class="form-control form-control-alternative{{ $errors->has('answer_audio2') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('answer_audio2'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('answer_audio2') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                       
                                        <div class="col-md-6 form-group{{ $errors->has('audio3') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio3">{{ __('Speaker3 Question Audio') }}</label>

                                            <input type="file" name="audio3" id="audio3" class="form-control form-control-alternative{{ $errors->has('audio3') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('audio3'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio3') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('answer_audio3') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="answer_audio3">{{ __('Speaker3 Answer Audio') }}</label>

                                            <input type="file" name="answer_audio3" id="answer_audio3" class="form-control form-control-alternative{{ $errors->has('answer_audio3') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('answer_audio3'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('answer_audio3') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('audio4') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio4">{{ __('Speaker4 Question Audio') }}</label>

                                            <input type="file" name="audio4" id="audio4" class="form-control form-control-alternative{{ $errors->has('audio4') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('audio4'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio4') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('answer_audio4') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="answer_audio4">{{ __('Speaker4 Answer Audio') }}</label>

                                            <input type="file" name="answer_audio4" id="answer_audio4" class="form-control form-control-alternative{{ $errors->has('answer_audio4') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('answer_audio4'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('answer_audio4') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('audio5') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio5">{{ __('Speaker5 Question Audio') }}</label>

                                            <input type="file" name="audio5" id="audio5" class="form-control form-control-alternative{{ $errors->has('audio5') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('audio5'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio5') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('answer_audio5') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="answer_audio4">{{ __('Speaker5 Answer Audio') }}</label>

                                            <input type="file" name="answer_audio5" id="answer_audio5" class="form-control form-control-alternative{{ $errors->has('answer_audio5') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('answer_audio5'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('answer_audio5') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('audio6') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio6">{{ __('Speaker6 Question Audio') }}</label>

                                            <input type="file" name="audio6" id="audio6" class="form-control form-control-alternative{{ $errors->has('audio6') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('audio6'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio6') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('answer_audio6') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="answer_audio4">{{ __('Speaker6 Answer Audio') }}</label>

                                            <input type="file" name="answer_audio6" id="answer_audio6" class="form-control form-control-alternative{{ $errors->has('answer_audio6') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('answer_audio6'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('answer_audio6') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('audio7') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio7">{{ __('Speaker7 Question Audio') }}</label>

                                            <input type="file" name="audio7" id="audio7" class="form-control form-control-alternative{{ $errors->has('audio7') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('audio7'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio7') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('answer_audio7') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="answer_audio7">{{ __('Speaker7 Answer Audio') }}</label>

                                            <input type="file" name="answer_audio7" id="answer_audio7" class="form-control form-control-alternative{{ $errors->has('answer_audio7') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('answer_audio7'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('answer_audio7') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('audio8') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio8">{{ __('Speaker8 Question Audio') }}</label>

                                            <input type="file" name="audio8" id="audio8" class="form-control form-control-alternative{{ $errors->has('audio8') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('audio8'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio8') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('answer_audio8') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="answer_audio8">{{ __('Speaker4 Answer Audio') }}</label>

                                            <input type="file" name="answer_audio8" id="answer_audio8" class="form-control form-control-alternative{{ $errors->has('answer_audio8') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('answer_audio8'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('answer_audio8') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('audio9') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio9">{{ __('Speaker9 Question Audio') }}</label>

                                            <input type="file" name="audio9" id="audio9" class="form-control form-control-alternative{{ $errors->has('audio9') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('audio9'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio9') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('answer_audio9') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="answer_audio9">{{ __('Speaker9 Answer Audio') }}</label>

                                            <input type="file" name="answer_audio9" id="answer_audio9" class="form-control form-control-alternative{{ $errors->has('answer_audio9') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('answer_audio9'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('answer_audio9') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('audio4') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio4">{{ __('Speaker10 Question Audio') }}</label>

                                            <input type="file" name="audio10" id="audio10" class="form-control form-control-alternative{{ $errors->has('audio10') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('audio10'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio10') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('answer_audio10') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="answer_audio10">{{ __('Speaker10 Answer Audio') }}</label>

                                            <input type="file" name="answer_audio10" id="answer_audio10" class="form-control form-control-alternative{{ $errors->has('answer_audio10') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('answer_audio10'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('answer_audio10') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('audio11') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio11">{{ __('Speaker11 Question Audio') }}</label>

                                            <input type="file" name="audio11" id="audio11" class="form-control form-control-alternative{{ $errors->has('audio11') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('audio11'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio11') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('answer_audio11') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="answer_audio11">{{ __('Speaker11 Answer Audio') }}</label>

                                            <input type="file" name="answer_audio11" id="answer_audio11" class="form-control form-control-alternative{{ $errors->has('answer_audio11') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('answer_audio11'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('answer_audio11') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('audio12') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio12">{{ __('Speaker12 Question Audio') }}</label>

                                            <input type="file" name="audio12" id="audio12" class="form-control form-control-alternative{{ $errors->has('audio12') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('audio12'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio12') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('answer_audio12') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="answer_audio12">{{ __('Speaker12 Answer Audio') }}</label>

                                            <input type="file" name="answer_audio12" id="answer_audio12" class="form-control form-control-alternative{{ $errors->has('answer_audio12') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('answer_audio12'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('answer_audio12') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('audio13') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio4">{{ __('Speaker13 Question Audio') }}</label>

                                            <input type="file" name="audio13" id="audio13" class="form-control form-control-alternative{{ $errors->has('audio13') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('audio13'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio13') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('answer_audio13') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="answer_audio13">{{ __('Speaker13 Answer Audio') }}</label>

                                            <input type="file" name="answer_audio13" id="answer_audio13" class="form-control form-control-alternative{{ $errors->has('answer_audio13') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('answer_audio13'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('answer_audio13') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('audio14') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio14">{{ __('Speaker14 Question Audio') }}</label>

                                            <input type="file" name="audio14" id="audio14" class="form-control form-control-alternative{{ $errors->has('audio14') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('audio14'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio14') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('answer_audio14') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="answer_audio14">{{ __('Speaker14 Answer Audio') }}</label>

                                            <input type="file" name="answer_audio14" id="answer_audio14" class="form-control form-control-alternative{{ $errors->has('answer_audio14') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('answer_audio14'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('answer_audio14') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('audio15') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio4">{{ __('Speaker15 Question Audio') }}</label>

                                            <input type="file" name="audio15" id="audio15" class="form-control form-control-alternative{{ $errors->has('audio15') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('audio15'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio15') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('answer_audio15') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="answer_audio15">{{ __('Speaker15 Answer Audio') }}</label>

                                            <input type="file" name="answer_audio15" id="answer_audio15" class="form-control form-control-alternative{{ $errors->has('answer_audio15') ? ' is-invalid' : '' }}" >

                                            @if ($errors->has('answer_audio15'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('answer_audio15') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                              
                                    </div>
                                    <hr>
                                    <div class="row" id="textfield">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="addHtml">
                                            </div>
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
           
            $('#template_type_id').change(function(){
               
                var template_type = $("#template_type_id option:selected").text();
                
                var lineno=template_type.split(' ');
                var html='';
                if(lineno[0]>0){
                    for(var index=0;index<=lineno[0];index++){
                        if(index==0){
                            var text="Question";
                        }else if(index==5){
                            var text="Answer";
                        }else{
                            var text="Option"+index;
                        }
                        
                         html += "<div class='col-md-6 form-group{{ $errors->has('text1') ? ' has-danger' : '' }}'>"+

                                    "<label class='form-control-label' for='text1'>"+text+"</label>"+

                                    '<input type="text" name="text[]" id="text1" class="form-control form-control-alternative{{ $errors->has("text1") ? " is-invalid" : "" }}" value="{{old("text1")}}" required>'+

                                    "@if ($errors->has('text1'))"+

                                        '<span class="invalid-feedback" role="alert">'+

                                            "<strong>{{ $errors->first('text1') }}</strong>"+

                                        "</span>"+

                                    "@endif"+

                                    "</div>"; 
                    }
                }

                $("#textfield").html(html);
                

            });
    </script>
@endsection
