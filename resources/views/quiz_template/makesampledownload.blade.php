@extends('layouts.app', ['title' => __('Template Management')])



@section('content')

    @include('template.partials.header', ['title' => __('Make Sample Template')])

<style>
.bg-orange{background-color:#feb06a;}
    .bg-default{background-color:#d4d4d4;}
    .shadow{box-shadow: 0px 0px 5px -2px rgba(0,0,0,0.75);}
    .text-bar{border-radius:15px;}
    .fz-14{font-size:16px;}
    .fw-500{font-weight:500;}
    .text-light-black{color:#333;}
</style>

    <div class="container-fluid mt--7">

        <div class="row">

            <div class="col-xl-12 order-xl-1">

                <div class="card bg-secondary shadow">

                    <div class="card-header bg-white border-0">

                        <div class="row align-items-center">

                            <div class="col-8">

                                <!--<h3 class="mb-0">{{ __('template Management') }}</h3>-->

                            </div>

                            <div class="col-4 text-right">

                                <a href="{{ route('quiz_template.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>

                            </div>

                        </div>

                    </div>

                    <div class="card-body">

                        <!--<h6 class="heading-small text-muted mb-4">{{ __('template information') }}</h6>-->

                        <div class="pl-lg-4">

                            <form method="post" action="{{ route('quiz_template.makesampledownload') }}" autocomplete="off" enctype="multipart/form-data">

                                @csrf

                                </div>

                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-md-3 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="type">{{ __('Template Name') }}</label>
                                             <input type="hidden" name="template_html" id="template_html">
                                               <input type="hidden" name="template_image_size" id="template_image_size">
                                              <input type="hidden" name="template_html_string" id="template_html_string">
                                               <input type="hidden" name="template_image" id="template_image">
                                            <input type="text" name="template_name" id="template_name" class="form-control form-control-alternative{{ $errors->has('template_name') ? ' is-invalid' : '' }}" required>
                                               
                                            
                                            @if ($errors->has('template_name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('template_name') }}</strong>
                                                </span>
                                            @endif
                                         
                                        </div>
                                        <div class="col-md-3 form-group{{ $errors->has('type') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="type">{{ __('Template Type') }}</label>
                                            <select name="name" id="name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                            @foreach($typeList as $res)
                                                <option value="{{$res->id}}">{{$res->type}}</option>
                                            @endforeach
                                            
                                            @if ($errors->has('name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group{{ $errors->has('ratio') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="ratio">{{ __('Ratio') }}</label>
                                            <select name="ratio" id="ratio" class="form-control form-control-alternative{{ $errors->has('ratio') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                            @foreach($ratioList as $res)
                                                <option value="{{$res->id}}">{{$res->name}}</option>
                                            @endforeach
                                            
                                            @if ($errors->has('ratio'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('ratio') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                       
                                        <div class="col-md-3 form-group{{ $errors->has('option_type_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="option_type_id">{{ __('Option Type') }}</label>
                                            <select name="option_type_id" id="option_type_id" class="form-control form-control-alternative{{ $errors->has('option_type_id') ? ' is-invalid' : '' }}" required>
                                            <option value=""> -- </option>
                                            @foreach($optionList as $res)
                                                <option value="{{$res->id}}">{{$res->type}}</option>
                                            @endforeach
                                            
                                            @if ($errors->has('option_type_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('option_type_id') }}</strong>
                                                </span>
                                            @endif
                                      
                                            </select>
                                        </div>
                                        <!--
                                         <div class="col-md-3 form-group{{ $errors->has('image') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="image">{{ __('Image') }}</label>

                                            <input type="file" name="image" id="image" class="form-control form-control-alternative{{ $errors->has('image') ? ' is-invalid' : '' }}" value="">

                                            @if ($errors->has('image'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('image') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        -->
                                       
                                        <div class="col-md-5 form-group{{ $errors->has('topic_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="topic_id">{{ __('Topic') }}</label>
                                            <select name="topic_id[]" id="topic_id" class="form-control form-control-alternative{{ $errors->has('topic_id') ? ' is-invalid' : '' }}" required multiple>
                                            <option value=""> -- </option>
                                           
                                            
                                            @if ($errors->has('topic_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('topic_id') }}</strong>
                                                </span>
                                            @endif
                                      
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group{{ $errors->has('pattern') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="pattern">{{ __('Pattern') }}</label>
                                            <select name="pattern" id="pattern" class="form-control form-control-alternative{{ $errors->has('pattern') ? ' is-invalid' : '' }}" required>
                                            <option value=""> -- </option>
                                            @foreach($patternList as $res)
                                                <option value="{{$res->id}}">{{$res->name}}</option>
                                            @endforeach
                                            
                                            @if ($errors->has('pattern'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('pattern') }}</strong>
                                                </span>
                                            @endif
                                      
                                            </select>
                                        </div>

                                        <div class="col-md-2 form-group{{ $errors->has('[backgroundcolor') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="color">{{ __('Background Color') }}</label>

                                            <input type="color" name="backgroundcolor" id="backgroundcolor" class="form-control form-control-alternative{{ $errors->has('backgroundcolor') ? ' is-invalid' : '' }}" value="{{old('backgroundcolor')}}" required>

                                            @if ($errors->has('backgroundcolor'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('backgroundcolor') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-2 form-group{{ $errors->has('answerbackgroundcolor') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="answerbackgroundcolor">{{ __('Answer Background Color') }}</label>

                                            <input type="color" name="answerbackgroundcolor" id="answerbackgroundcolor" class="form-control form-control-alternative{{ $errors->has('answerbackgroundcolor') ? ' is-invalid' : '' }}" value="{{old('answerbackgroundcolor')}}" required>

                                            @if ($errors->has('answerbackgroundcolor'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('answerbackgroundcolor') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-12">Question</div>
                                        <div class="col-md-3 form-group{{ $errors->has('question_fonts') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="question_fonts">{{ __('Fonts') }}</label>
                                            <select name="question_fonts" id="question_fonts" class="form-control fonts form-control-alternative{{ $errors->has('question_fonts') ? ' is-invalid' : '' }}" value="{{old('question_fonts')}}" required >

                                            @if ($errors->has('question_fonts'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('question_fonts') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-3 form-group{{ $errors->has('question_color') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="question_color">{{ __('Fonts Color') }}</label>

                                            <input type="color" name="question_color" id="question_color" class="form-control form-control-alternative{{ $errors->has('question_color') ? ' is-invalid' : '' }}" value="" >

                                            @if ($errors->has('question_color'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('question_color') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class=" col-md-3 form-group{{ $errors->has('[question_textbgcolor') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="question_textbgcolor">{{ __('Font Background') }}</label>

                                            <input type="color" name="question_textbgcolor" id="question_textbgcolor" class="form-control form-control-alternative{{ $errors->has('question_textbgcolor') ? ' is-invalid' : '' }}" placeholder="{{ __('question_textbgcolor') }}" value="{{old('question_textbgcolor')}}">

                                            @if ($errors->has('question_textbgcolor'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('question_textbgcolor') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        <div class=" col-md-3 form-group{{ $errors->has('question_bordercolor') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="question_bordercolor">{{ __('Border Color') }}</label>

                                            <input type="color" name="question_bordercolor" id="question_bordercolor" class="form-control form-control-alternative{{ $errors->has('question_bordercolor') ? ' is-invalid' : '' }}" placeholder="{{ __('question_bordercolor') }}" value="{{old('question_bordercolor')}}" >

                                            @if ($errors->has('question_bordercolor'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('question_bordercolor') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        <div class="col-md-12">Options</div>
                                        <div class="col-md-4 form-group{{ $errors->has('optionfonts') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="optionfonts">{{ __('Fonts') }}</label>
                                            <select name="optionfonts" id="optionfonts" class="form-control fonts form-control-alternative{{ $errors->has('optionfonts') ? ' is-invalid' : '' }}" value="{{old('optionfonts')}}" required>

                                            @if ($errors->has('optionfonts'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('optionfonts') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-4 form-group{{ $errors->has('optioncolor') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="optioncolor">{{ __('Fonts Color') }}</label>

                                            <input type="color" name="optioncolor" id="optioncolor" class="form-control form-control-alternative{{ $errors->has('optioncolor') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('optioncolor'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('optioncolor') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class=" col-md-4 form-group{{ $errors->has('optiontextbgcolor') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="optiontextbgcolor">{{ __('Font Background') }}</label>

                                            <input type="color" name="optiontextbgcolor" id="optiontextbgcolor" class="form-control form-control-alternative{{ $errors->has('optiontextbgcolor') ? ' is-invalid' : '' }}" placeholder="{{ __('optiontextbgcolor') }}" value="{{old('option_textbgcolor')}}" >

                                            @if ($errors->has('optiontextbgcolor'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('optiontextbgcolor') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        <div class="col-md-12">Answer</div>
                                        <div class="col-md-3 form-group{{ $errors->has('option_fonts') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="option_fonts">{{ __('Fonts') }}</label>
                                            <select name="option_fonts" id="option_fonts" class="form-control fonts form-control-alternative{{ $errors->has('option_fonts') ? ' is-invalid' : '' }}" value="{{old('option_fonts')}}" required >

                                            @if ($errors->has('option_fonts'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('option_fonts') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-3 form-group{{ $errors->has('option_color') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="option_color">{{ __('Fonts Color') }}</label>

                                            <input type="color" name="option_color" id="option_color" class="form-control form-control-alternative{{ $errors->has('option_color') ? ' is-invalid' : '' }}" value="">

                                            @if ($errors->has('option_color'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('option_color') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class=" col-md-3 form-group{{ $errors->has('option_textbgcolor') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="option_textbgcolor">{{ __('Font Background') }}</label>

                                            <input type="color" name="option_textbgcolor" id="option_textbgcolor" class="form-control form-control-alternative{{ $errors->has('option_textbgcolor') ? ' is-invalid' : '' }}" placeholder="{{ __('option_textbgcolor') }}" value="{{old('option_textbgcolor')}}">

                                            @if ($errors->has('option_textbgcolor'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('option_textbgcolor') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        <div class=" col-md-3 form-group{{ $errors->has('option_bordercolor') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="option_bordercolor">{{ __('Border Color') }}</label>

                                            <input type="color" name="option_bordercolor" id="option_bordercolor" class="form-control form-control-alternative{{ $errors->has('option_bordercolor') ? ' is-invalid' : '' }}" placeholder="{{ __('option_bordercolor') }}" value="{{old('option_bordercolor')}}">

                                            @if ($errors->has('option_bordercolor'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('option_bordercolor') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                     
                                    </div>  
                                   
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success mt-4" >{{ __('Save') }}</button>

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script type="text/javascript">


            var googleFontUrl = 'https://fonts.googleapis.com/css?family=Open+Sans, sans-serif:400,700,700 Italic|Merriweather,serif:300,400,700 Italic|Ubuntu, sans-serif:300,300 Italic|Roboto,sans-serif:300,300 Italic|Lato,sans-serif:300,300 Italic|poppins,sans-serif:400,400 Italic|Raleway,sans-serif:400,400 Italic|Montserrat,sans-serif:400,400 Italic|Source Sans 3, sans-serif:400,400 Italic';
            var fontFamilies = getParameterByName('family', googleFontUrl).split('|');
            console.log(fontFamilies);
            var fontArr = [];
            fontFamilies.forEach(function(item) {
               fontname= item.split(':');
               fontvalue = fontname[1].split(',');
               fontvalue.forEach(function(val) {
                 fontArr.push(item.split(':')[0]+':'+val);
               });
            //fontArr.push(item.split(':')[0]);
                console.log(item);
            });
            console.log(fontArr);
            var font_option='<option value=""> -- </option>';
            for(var counter=0;counter<fontArr.length;counter++){
                font_option += '<option value="'+fontArr[counter]+'">'+fontArr[counter]+'</option>'
            }
            $('.fonts').html(font_option);
            function getParameterByName(name, url) {
                if (!url) url = window.location.href;
                name = name.replace(/[\[\]]/g, "\\$&");
                var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                    results = regex.exec(url);
                if (!results) return null;
                if (!results[2]) return '';
                return decodeURIComponent(results[2].replace(/\+/g, " "));
            }

              $('#name').change(function(){
                var template_name = $("#name").val();
                if(template_name==''){
                    template_name ='0';
                }
                
                var ratio = $("#ratio").val();
                   if(ratio==''){
                    ratio ='0';
                }
                var option_type_id = $("#option_type_id").val();
                   if(option_type_id==''){
                    option_type_id ='0';
                }
                 var base_url='<?php echo env('BASE_URL')?>';
              
                  $.ajax({

                    method: 'get',
        
                    url: base_url+'/quizpattern/gettemplatepattern/'+template_name+'/'+ratio+'/'+option_type_id,
        
                }).then(response => {
        
                    if (response.status == true) {
        
                        var result = response.data;
                        var html ='<option value=""></option>';
                        for(var counter=0;counter<result.length;counter++){
                            html +='<option value="'+result[counter].id+'">'+result[counter].name+'</option>';
                        }
                        $("#pattern").html(html);
                       
                    } else {
        
                        
                        $("#pattern").html('<option value="">---------</option>');
                    
        
                    }
        
                    
        
                }).catch(function (error) {
        
                    console.log(error);
        
                });
            });
            
             $('#ratio').change(function(){
                var template_name = $("#name").val();
                if(template_name==''){
                    template_name ='0';
                }
                
                var ratio = $("#ratio").val();
                   if(ratio==''){
                    ratio ='0';
                }
                var option_type_id = $("#option_type_id").val();
                   if(option_type_id==''){
                    option_type_id ='0';
                }
                 var base_url='<?php echo env('BASE_URL')?>';
              
                  $.ajax({

                    method: 'get',
        
                    url: base_url+'/quizpattern/gettemplatepattern/'+template_name+'/'+ratio+'/'+option_type_id,
        
                }).then(response => {
        
                    if (response.status == true) {
        
                        var result = response.data;
                        var html ='<option value=""></option>';
                        for(var counter=0;counter<result.length;counter++){
                            html +='<option value="'+result[counter].id+'">'+result[counter].name+'</option>';
                        }
                        $("#pattern").html(html);
                       
                    } else {
                           $("#pattern").html('<option value="">---------</option>');
        
                    }
        
                    
        
                }).catch(function (error) {
        
                    console.log(error);
        
                });
            });
            
            $('#option_type_id').change(function(){
                var template_name = $("#name").val();
                if(template_name==''){
                    template_name ='0';
                }
                
                var ratio = $("#ratio").val();
                   if(ratio==''){
                    ratio ='0';
                }
                var option_type_id = $("#option_type_id").val();
                   if(option_type_id==''){
                    option_type_id ='0';
                }
                 var base_url='<?php echo env('BASE_URL')?>';
              
                  $.ajax({

                    method: 'get',
        
                    url: base_url+'/quizpattern/gettemplatepattern/'+template_name+'/'+ratio+'/'+option_type_id,
        
                }).then(response => {
                    if (response.status == true) {
        
                        var result = response.data;
                        var topics = response.topics;
                        
                        if(topics.length>0){
                            var topichtml ='<option value=""></option>';
                             for(var counter=0;counter<topics.length;counter++){
                                topichtml +='<option value="'+topics[counter].id+'">'+topics[counter].name+'</option>';
                            }
                            $("#topic_id").html(topichtml);
                        }
                        var html ='<option value=""></option>';
                        for(var counter=0;counter<result.length;counter++){
                            html +='<option value="'+result[counter].id+'">'+result[counter].name+'</option>';
                        }
                        $("#pattern").html(html);
                       
                    } else {
        
                          $("#pattern").html('<option value="">---------</option>');
        
                    
        
                    }
        
                    
        
                }).catch(function (error) {
        
                    console.log(error);
        
                });
            });
            
           
        </script>

        @include('layouts.footers.auth')

    </div>

@endsection

