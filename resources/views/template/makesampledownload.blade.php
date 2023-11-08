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

                                <a href="{{ route('template.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>

                            </div>

                        </div>

                    </div>

                    <div class="card-body">

                        <!--<h6 class="heading-small text-muted mb-4">{{ __('template information') }}</h6>-->

                        <div class="pl-lg-4">

                            <form method="post" action="{{ route('template.makesampledownload') }}" autocomplete="off" enctype="multipart/form-data">

                                @csrf

                                </div>

                                <div class="pl-lg-4">
                                    
                                    <div class="row">
                                        <div class="col-md-3 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="type">{{ __('Template Name') }}</label>
                                             <!--<input type="hidden" name="template_html" id="template_html">-->
                                             <!--  <input type="hidden" name="template_image_size" id="template_image_size">-->
                                             <!-- <input type="hidden" name="template_html_string" id="template_html_string">-->
                                             <!--  <input type="hidden" name="template_image" id="template_image">-->
                                            <input type="text" name="template_name" id="template_name" class="form-control form-control-alternative{{ $errors->has('template_name') ? ' is-invalid' : '' }}" required>
                                               
                                            
                                            @if ($errors->has('template_name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('template_name') }}</strong>
                                                </span>
                                            @endif
                                         
                                        </div>
                                        <div class="col-md-3 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
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
                                        <div class="col-md-3 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="type">{{ __('Ratio') }}</label>
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
                                        <div class="col-md-3 form-group{{ $errors->has('type') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="parent_id">{{ __('Image Type') }}</label>
                                            <select name="type" id="type" class="form-control form-control-alternative{{ $errors->has('type') ? ' is-invalid' : '' }}" value="{{old('type')}}" required>
                                                <option value=""> -- </option>
                                                <option value="with_image">With Image</option>
                                                <option value="without_image">Without Image</option>
                                            
                                            @if ($errors->has('type'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('type') }}</strong>
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
                                        <div class="col-md-3 form-group{{ $errors->has('pattern') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="video_type">{{ __('Video Type') }}</label>
                                            <select name="video_type" id="video_type" class="form-control form-control-alternative{{ $errors->has('video_type') ? ' is-invalid' : '' }}" required>
                                            <option value=""> -- </option>
                                            @foreach($videoList as $res)
                                                <option value="{{$res->id}}">{{$res->name}}</option>
                                            @endforeach
                                            
                                            @if ($errors->has('video_type'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('video_type') }}</strong>
                                                </span>
                                            @endif
                                      
                                            </select>
                                        </div>
                                         <div class="col-md-6 form-group{{ $errors->has('subcategory') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="video_type">{{ __('SubCategory') }}</label>
                                            <select name="subcategory[]" id="subcategory" class="form-control form-control-alternative{{ $errors->has('subcategory') ? ' is-invalid' : '' }}" required multiple>
                                            <option value=""> -- </option>
                                           
                                            
                                            @if ($errors->has('subcategory'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('subcategory') }}</strong>
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
                                        <div class="col-md-4 form-group{{ $errors->has('fonts') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="ratio">{{ __('Fonts') }}</label>
                                                <select name="fonts" id="fonts1" class="form-control fonts form-control-alternative{{ $errors->has('fonts') ? ' is-invalid' : '' }}" value="{{old('fonts')}}" required >
    
                                                @if ($errors->has('fonts'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('fonts') }}</strong>
                                                    </span>
                                                @endif
                                                </select>
                                        </div>
                                          

                                        <div class="col-md-4 form-group{{ $errors->has('[backgroundcolor') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="color">{{ __('Background Color') }}</label>

                                            <input type="color" name="backgroundcolor" id="backgroundcolor" class="form-control form-control-alternative{{ $errors->has('backgroundcolor') ? ' is-invalid' : '' }}" value="{{old('backgroundcolor')}}" required>

                                            @if ($errors->has('backgroundcolor'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('backgroundcolor') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                         <div class="col-md-4 form-group{{ $errors->has('bordercolor') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="color">{{ __('Image Border Color') }}</label>

                                            <input type="color" name="bordercolor" id="bordercolor" class="form-control form-control-alternative{{ $errors->has('bordercolor') ? ' is-invalid' : '' }}" value="{{old('bordercolor')}}" required>

                                            @if ($errors->has('bordercolor'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('bordercolor') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <!-- 
                                        <div class="col-md-12">Line1</div>
                                            <div class="col-md-4 form-group{{ $errors->has('fonts') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="ratio">{{ __('Fonts') }}</label>
                                                <select name="fonts" id="fonts1" class="form-control fonts form-control-alternative{{ $errors->has('fonts') ? ' is-invalid' : '' }}" value="{{old('fonts')}}" required >
    
                                                @if ($errors->has('fonts'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('fonts') }}</strong>
                                                    </span>
                                                @endif
                                                </select>
                                            </div>
                                          
                                            <div class="col-md-4 form-group{{ $errors->has('color') ? ' has-danger' : '' }}">
    
                                                <label class="form-control-label" for="color">{{ __('Fonts Color') }}</label>
    
                                                <input type="color" name="color[]" id="color1" class="form-control form-control-alternative{{ $errors->has('color') ? ' is-invalid' : '' }}" value="" required onchange="getFontColor('1',this.value)">
    
                                                @if ($errors->has('color'))
    
                                                    <span class="invalid-feedback" role="alert">
    
                                                        <strong>{{ $errors->first('color') }}</strong>
    
                                                    </span>
    
                                                @endif
    
                                            </div> 
                                            <div class=" col-md-4 form-group{{ $errors->has('[text') ? ' has-danger' : '' }}">
    
                                                <label class="form-control-label" for="template_title">{{ __('Font Background') }}</label>
    
                                                <input type="color" name="textbg[]" id="textbg1" class="form-control form-control-alternative{{ $errors->has('text') ? ' is-invalid' : '' }}" placeholder="{{ __('text') }}" value="{{old('text')}}" required onchange="getFontBackgroundColor('1',this.value)">
    
                                                @if ($errors->has('text'))
    
                                                    <span class="invalid-feedback" role="alert">
    
                                                        <strong>{{ $errors->first('text') }}</strong>
    
                                                    </span>
    
                                                @endif
    
                                            </div> -->
                                        
                                     
                                    </div>    
                                    <!-- <div class="row" id="extra_html"></div> -->
                                
                                    <!-- <div> -->
                                        <!-- <div class="row">
                                            <div class="offset-md-3 col-md-6 offset-lg-3 col-lg-6" id="pattern_html">
                                            </div>
                                        </div -->
                                        
                                    <!-- </div> -->
                                    <div class="text-center">
                                        <!--<button type="button" class="btn btn-success mt-4" id="saveImage">{{ __('Save') }}</button>-->
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

          /*  $('#name').change(function(){
                 $("#subcategory").empty();
                $("#pattern").empty();
                var template_name = $("#name").val();
                if(template_name==''){
                    template_name ='0';
                }
                 var image_type = $("#type").val();
                   if(image_type==''){
                    image_type ='0';
                }
                var ratio = $("#ratio").val();
                   if(ratio==''){
                    ratio ='0';
                }
                var video_type = $("#video_type").val();
                   if(video_type==''){
                    video_type ='0';
                }
                 var base_url='<?php echo env('BASE_URL')?>';
                //$('#preview_name').html($("#name option:selected").text());
                  $.ajax({

                    method: 'get',
        
                    url: base_url+'/pattern/gettemplatepattern/'+template_name+'/'+image_type+'/'+ratio+'/'+video_type,
        
                   // data: {pattern_id:pattern_id},
        
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
            });*/
            
      /*       $('#ratio').change(function(){
                  $("#subcategory").empty();
                $("#pattern").empty();
                var template_name = $("#name").val();
                if(template_name==''){
                    template_name ='0';
                }
                 var image_type = $("#type").val();
                   if(image_type==''){
                    image_type ='0';
                }
                var ratio = $("#ratio").val();
                   if(ratio==''){
                    ratio ='0';
                }
                var video_type = $("#video_type").val();
                   if(video_type==''){
                    video_type ='0';
                }
                 var base_url='<?php echo env('BASE_URL')?>';
                //$('#preview_name').html($("#name option:selected").text());
                  $.ajax({

                    method: 'get',
        
                    url: base_url+'/pattern/gettemplatepattern/'+template_name+'/'+image_type+'/'+ratio+'/'+video_type,
        
                   // data: {pattern_id:pattern_id},
        
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
            });*/
            
          /*  $('#type').change(function(){
                 $("#subcategory").empty();
                $("#pattern").empty();
                
                var template_name = $("#name").val();
                 if(template_name==''){
                    template_name ='0';
                }
                 var image_type = $("#type").val();
                   if(image_type==''){
                    image_type ='0';
                }
                var ratio = $("#ratio").val();
                   if(ratio==''){
                    ratio ='0';
                }
                var video_type = $("#video_type").val();
                   if(video_type==''){
                    video_type ='0';
                }
                 var base_url='<?php echo env('BASE_URL')?>';
                //$('#preview_name').html($("#name option:selected").text());
                  $.ajax({

                    method: 'get',
        
                    url: base_url+'/pattern/gettemplatepattern/'+template_name+'/'+image_type+'/'+ratio+'/'+video_type,
        
                   // data: {pattern_id:pattern_id},
        
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
            });*/
            $('#video_type').change(function(){
                
                $("#subcategory").empty();
                $("#pattern").empty();
                   
                var template_name = $("#name").val();
                 if(template_name==''){
                    template_name ='0';
                }
                 var image_type = $("#type").val();
                   if(image_type==''){
                    image_type ='0';
                }
                var ratio = $("#ratio").val();
                   if(ratio==''){
                    ratio ='0';
                }
                var video_type = $("#video_type").val();
                   if(video_type==''){
                    video_type ='0';
                }
                 var base_url='<?php echo env('BASE_URL')?>';
                //$('#preview_name').html($("#name option:selected").text());
                  $.ajax({

                    method: 'get',
        
                    url: base_url+'/pattern/gettemplatepattern/'+template_name+'/'+image_type+'/'+ratio+'/'+video_type,
        
                   // data: {pattern_id:pattern_id},
        
                }).then(response => {
        
                    if (response.status == true) {
        
                        var result = response.data;
                         var subcategories = response.subcategories;
                        var html ='<option value=""></option>';
                        for(var counter=0;counter<result.length;counter++){
                            html +='<option value="'+result[counter].id+'">'+result[counter].name+'</option>';
                        }
                        $("#pattern").html(html);
                        if(subcategories.length>0){
                            var subcategoryhtml ='<option value=""></option>';
                             for(var counter=0;counter<subcategories.length;counter++){
                                subcategoryhtml +='<option value="'+subcategories[counter].id+'">'+subcategories[counter].name+'</option>';
                            }
                            $("#subcategory").html(subcategoryhtml);
                        }
                       
                       
                    } else {
        
                          $("#pattern").html('<option value="">---------</option>');
        
                    
        
                    }
        
                    
        
                }).catch(function (error) {
        
                    console.log(error);
        
                });
            });
            // $('#pattern').change(function(){
            //     // var style=$('#preview_style').attr('style');
            //     // style +=';text-align:'+$(this).val();
               
            //     // $('#preview_style').attr('style',style);
            //     var pattern_id = $(this).val();
            //     var base_url='<?php echo env('BASE_URL')?>';

            //     $.ajax({

            //         method: 'get',
        
            //         url: base_url+'/pattern/getpattern/'+pattern_id,
        
            //       // data: {pattern_id:pattern_id},
        
            //     }).then(response => {
        
            //         if (response.status == true) {debugger;
        
            //             var result = response.data;
                        
            //             var image_url = base_url + 'public/uploads/pattern_image/default-img.png'
            //             result= result.replace("[image_url]", image_url);
            //             var templatelength=response.template_type;
            //               var html='';
            //             for(var index=1;index<templatelength;index++){
                          
            //                 var line_no= index+1;
            //                 var font_line_no = 'fonts'+line_no;
            //                 html +='<div class="col-md-12">Line'+line_no+'</div>'+
            //                 '<div class="col-md-4 form-group{{ $errors->has('fonts') ? ' has-danger' : '' }}">'+
            //                                 '<label class="form-control-label" for="ratio">{{ __('Fonts') }}</label>'+
            //                                 '<select name="fonts[]" id="fonts'+line_no+'" class="fonts form-control form-control-alternative{{ $errors->has('fonts') ? ' is-invalid' : '' }}" value="{{old('fonts')}}" required onchange="getFont('+font_line_no+','+this.value+')">'+

            //                                 '@if ($errors->has('fonts'))
            //                                     <span class="invalid-feedback" role="alert">'+'
            //                                         '<strong>{{ $errors->first('fonts') }}</strong>'+
            //                                     '</span>'+
            //                                 '@endif'+
            //                                 '</select>'+
            //                             '</div>'+
                                        
            //                             '<div class="col-md-4 form-group{{ $errors->has('[color') ? ' has-danger' : '' }}">'+

            //                                 '<label class="form-control-label" for="color">{{ __('Fonts Color') }}</label>'+

            //                                 '<input type="color" name="color[]" id="color'+line_no+'" class="form-control form-control-alternative{{ $errors->has('color') ? ' is-invalid' : '' }}" value="" required onchange="getFontColor('+line_no+','+this.value+')">'+

            //                                 '@if ($errors->has('color'))'+

            //                                     '<span class="invalid-feedback" role="alert">'+

            //                                         '<strong>{{ $errors->first('color') }}</strong>'+

            //                                     '</span>'+

            //                                 '@endif'+

            //                             '</div>'+ 
            //                             '<div class=" col-md-4 form-group{{ $errors->has('[text') ? ' has-danger' : '' }}">'+

            //                                 '<label class="form-control-label" for="template_title">{{ __('Font Background') }}</label>'+

            //                                 '<input type="color" name="textbg[]" id="textbg'+line_no+'" class="form-control form-control-alternative{{ $errors->has('text') ? ' is-invalid' : '' }}" placeholder="{{ __('text') }}" value="{{old('text')}}" required onchange="getFontBackgroundColor('+line_no+','+this.value+')">'+

            //                                 '@if ($errors->has('text'))'+

            //                                     '<span class="invalid-feedback" role="alert">'+

            //                                         '<strong>{{ $errors->first('text') }}</strong>'+

            //                                     '</span>'+

            //                                 '@endif'+

            //                             '</div>';
                            
            //             }
            //             $('#pattern_html').html(result);
            //             $('#extra_html').html(html);
            //               $('.fonts').html(font_option);
            //               $("#template_image_size").val(response.image_size);
            //         } else {
        
                        
        
                    
        
            //         }
        
                    
        
            //     }).catch(function (error) {
        
            //         console.log(error);
        
            //     });
               
            // });
            // $('#fonts1').change(function(){
            
            //     // var style=$('#preview_style').attr('style');
            //     // if(style.includes('font-style')){
            //     //     style= style.replace('font-style:Italic','');
            //     // }\
            //     var style=$('.linefonts1').attr('style');
            //      var font = $(this).val();
            //      var fontarray=font.split(':');
            //      var fontstyle= fontarray[1].split(' ');
            //      if(fontstyle.length>1){
            //          style +=';font-style:'+fontstyle[1];
            //      }
            //      style +=';font-family:'+fontarray[0];
            //      style +=';font-weight:'+fontstyle[0];
                
            //       $('.linefonts1').attr('style',style);
            // });
            // $('#backgroundcolor').change(function(){

            //     var style=$('.backgroundc').attr('style');
            //     style +=';background-color:'+$(this).val();
               
            //     $('.backgroundc').attr('style',style);
                
            //     var bodystyle=$('#backgroundc').attr('style');
            //     bodystyle +=';background-color:'+$(this).val();
               
            //     $('#backgroundc').attr('style',bodystyle);
            // });
            // $('#bordercolor').change(function(){

            //   // var style=$('.backgroundc').attr('style');
            //   // var style ='border:1px solid '+$(this).val();
            //   var style=$('.imgborder').attr('style');
            //     style +=';border:1px solid '+$(this).val();
            //     $('.imgborder').attr('style',style);
            // });
            // $('#text').keyup(function(){
            //     $('#preview_text').html($('#text').val());
            // });
            
           
           
           
            
           
        </script>

        @include('layouts.footers.auth')

    </div>

@endsection

