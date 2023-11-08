@extends('layouts.app', ['title' => __('Video Management')])

@section('content')
    @include('video.partials.header', ['title' => __('Add Video')])

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
                                <a href="{{ route('video.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">{{ __('Video information') }}</h6>
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('video.store') }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                <!-- </div> -->
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-md-6 form-group{{ $errors->has('section') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="parent_id">{{ __('Video Type') }}</label>
                                            <select name="section" id="section" class="form-control form-control-alternative{{ $errors->has('category_id') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                            @foreach($sectionList as $res)
                                                <option value="{{$res->id}}">{{$res->name}}</option>
                                            @endforeach
                                            
                                            @if ($errors->has('section'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('section') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('template_type') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="template_type">{{ __('Template Type') }}</label>
                                            <select name="template_type" id="template_type" class="form-control form-control-alternative{{ $errors->has('template_type') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                            @foreach($templateTypeList as $res)
                                                <option value="{{$res->id}}">{{$res->type}}</option>
                                            @endforeach
                                            
                                            @if ($errors->has('template_type'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('template_type') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('primary_language') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="primary_language">{{ __('Primary Language') }}</label>
                                            <select name="primary_language" id="primary_language" class="form-control form-control-alternative{{ $errors->has('primary_language') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                            @foreach($primaryLanguageList as $res)
                                                <option value="{{$res->id}}">{{$res->name}}</option>
                                            @endforeach
                                            
                                            @if ($errors->has('primary_language'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('primary_language') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('secondary_language') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="subcategory">{{ __('Secondary Language') }}</label>
                                            <select name="secondary_language" id="secondary_language" class="form-control form-control-alternative{{ $errors->has('secondary_language') ? ' is-invalid' : '' }}" value="old('secondary_language')" required>
                                                <option value=""> -- </option>
                                            @if ($errors->has('secondary_language'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('secondary_language') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('category') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="category">{{ __('Category Name') }}</label>
                                            <select name="category" id="category" class="form-control form-control-alternative{{ $errors->has('category') ? ' is-invalid' : '' }}" required>
                                                <option value=""> -- </option>
                                            @foreach($categoryList as $res)
                                                <option value="{{$res->id}}">{{$res->name}}</option>
                                            @endforeach
                                            
                                            @if ($errors->has('category'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('category') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('subcategory') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="subcategory">{{ __('Sub Category Name') }}</label>
                                            <select name="subcategory" id="subcategory" class="form-control form-control-alternative{{ $errors->has('subcategory') ? ' is-invalid' : '' }}" value="old('subcategory')" required>
                                                <option value=""> -- </option>
                                            @if ($errors->has('subcategory'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('subcategory') }}</strong>
                                                </span>
                                            @endif
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('image') ? ' has-danger' : '' }}">

                                        <label class="form-control-label" for="image">{{ __('Image') }}</label>

                                        <input type="file" name="image" id="image" class="form-control form-control-alternative{{ $errors->has('image') ? ' is-invalid' : '' }}" value="" required>

                                        @if ($errors->has('image'))

                                            <span class="invalid-feedback" role="alert">

                                                <strong>{{ $errors->first('image') }}</strong>

                                            </span>

                                        @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('audio_m') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="color">{{ __('Audio Male1 (Short)') }}</label>

                                            <input type="file" name="audio_m" id="audio_m" class="form-control form-control-alternative{{ $errors->has('audio_m') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_m'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_m') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('audio_m1') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="color">{{ __('Audio Male2 (Short)') }}</label>

                                            <input type="file" name="audio_m1" id="audio_m1" class="form-control form-control-alternative{{ $errors->has('audio_m1') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_m1'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_m1') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('audio_m2') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="color">{{ __('Audio Male3 (Short)') }}</label>

                                            <input type="file" name="audio_m2" id="audio_m2" class="form-control form-control-alternative{{ $errors->has('audio_m2') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_m2'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_m2') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('audio_m3') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="color">{{ __('Audio Male4 (Short)') }}</label>

                                            <input type="file" name="audio_m3" id="audio_m3" class="form-control form-control-alternative{{ $errors->has('audio_m3') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_m3'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_m3') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('audio_m4') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="color">{{ __('Audio Male5 (Short)') }}</label>

                                            <input type="file" name="audio_m4" id="audio_m4" class="form-control form-control-alternative{{ $errors->has('audio_m4') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_m4'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_m4') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('audio_f') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio_f">{{ __('Audio Female1 (Short)') }}</label>

                                            <input type="file" name="audio_f" id="audio_f" class="form-control form-control-alternative{{ $errors->has('audio_f') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_f'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_f') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('audio_f1') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio_f1">{{ __('Audio Female2 (Short)') }}</label>

                                            <input type="file" name="audio_f1" id="audio_f1" class="form-control form-control-alternative{{ $errors->has('audio_f1') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_f1'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_f1') }}</strong>

                                                </span>

                                            @endif

                                        </div> 
                                        <div class="col-md-6 form-group{{ $errors->has('audio_f2') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio_f2">{{ __('Audio Female3 (Short)') }}</label>

                                            <input type="file" name="audio_f2" id="audio_f2" class="form-control form-control-alternative{{ $errors->has('audio_f2') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_f2'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_f2') }}</strong>

                                                </span>

                                            @endif

                                        </div> 

                                        <div class="col-md-6 form-group{{ $errors->has('audio_f3') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio_f3">{{ __('Audio Female4 (Short)') }}</label>

                                            <input type="file" name="audio_f3" id="audio_f3" class="form-control form-control-alternative{{ $errors->has('audio_f3') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_f3'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_f3') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        <div class="col-md-6 form-group{{ $errors->has('audio_f4') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio_f4">{{ __('Audio Female5 (Short)') }}</label>

                                            <input type="file" name="audio_f4" id="audio_f4" class="form-control form-control-alternative{{ $errors->has('audio_f4') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_f4'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_f4') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                        
                                        <div class="col-md-6 form-group{{ $errors->has('audio_m1') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="color">{{ __('Audio Male1 (Long)') }}</label>

                                            <input type="file" name="audio_m1_long" id="audio_m1_long" class="form-control form-control-alternative{{ $errors->has('audio_m1_long') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_m1_long'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_m1_long') }}</strong>

                                                </span>

                                            @endif

                                            </div> 
                                            <div class="col-md-6 form-group{{ $errors->has('audio_m2_long') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="color">{{ __('Audio Male2 (Long)') }}</label>

                                            <input type="file" name="audio_m2_long" id="audio_m2_long" class="form-control form-control-alternative{{ $errors->has('audio_m2_long') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_m2_long'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_m2_long') }}</strong>

                                                </span>

                                            @endif

                                            </div> 
                                            <div class="col-md-6 form-group{{ $errors->has('audio_m3_long') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="color">{{ __('Audio Male3 (Long)') }}</label>

                                            <input type="file" name="audio_m3_long" id="audio_m3_long" class="form-control form-control-alternative{{ $errors->has('audio_m3_long') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_m3_long'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_m3_long') }}</strong>

                                                </span>

                                            @endif

                                            </div> 
                                            <div class="col-md-6 form-group{{ $errors->has('audio_m4_long') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="color">{{ __('Audio Male4 (Long)') }}</label>

                                            <input type="file" name="audio_m4_long" id="audio_m4_long" class="form-control form-control-alternative{{ $errors->has('audio_m4_long') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_m4_long'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_m4_long') }}</strong>

                                                </span>

                                            @endif

                                            </div> 
                                            <div class="col-md-6 form-group{{ $errors->has('audio_m5_long') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="color">{{ __('Audio Male5 (Long)') }}</label>

                                            <input type="file" name="audio_m5_long" id="audio_m5_long" class="form-control form-control-alternative{{ $errors->has('audio_m5_long') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_m5_long'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_m5_long') }}</strong>

                                                </span>

                                            @endif

                                            </div> 
                                            <div class="col-md-6 form-group{{ $errors->has('audio_f1_long') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio_f1_long">{{ __('Audio Female1 (Long)') }}</label>

                                            <input type="file" name="audio_f1_long" id="audio_f1_long" class="form-control form-control-alternative{{ $errors->has('audio_f1_long') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_f1_long'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_f1_long') }}</strong>

                                                </span>

                                            @endif

                                            </div> 
                                            <div class="col-md-6 form-group{{ $errors->has('audio_f2_long') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio_f2_long">{{ __('Audio Female2 (Long)') }}</label>

                                            <input type="file" name="audio_f2_long" id="audio_f2_long" class="form-control form-control-alternative{{ $errors->has('audio_f2_long') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_f2_long'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_f2_long') }}</strong>

                                                </span>

                                            @endif

                                            </div> 
                                            <div class="col-md-6 form-group{{ $errors->has('audio_f3_long') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio_f3_long">{{ __('Audio Female3 (Long)') }}</label>

                                            <input type="file" name="audio_f3_long" id="audio_f3_long" class="form-control form-control-alternative{{ $errors->has('audio_f3_long') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_f3_long'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_f3_long') }}</strong>

                                                </span>

                                            @endif

                                            </div> 

                                            <div class="col-md-6 form-group{{ $errors->has('audio_f4_long') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio_f4_long">{{ __('Audio Female4 (Long)') }}</label>

                                            <input type="file" name="audio_f4_long" id="audio_f4_long" class="form-control form-control-alternative{{ $errors->has('audio_f4_long') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_f4_long'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_f4_long') }}</strong>

                                                </span>

                                            @endif

                                            </div>
                                            <div class="col-md-6 form-group{{ $errors->has('audio_f5_long') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="audio_f5_long">{{ __('Audio Female5 (Long)') }}</label>

                                            <input type="file" name="audio_f5_long" id="audio_f5_long" class="form-control form-control-alternative{{ $errors->has('audio_f5_long') ? ' is-invalid' : '' }}" value="" required>

                                            @if ($errors->has('audio_f5_long'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('audio_f5_long') }}</strong>

                                                </span>

                                            @endif

                                            </div> 
                                        <!--<div class="col-md-6 form-group{{ $errors->has('audio_m') ? ' has-danger' : '' }}">-->

                                        <!--<label class="form-control-label" for="color">{{ __('Audio Male') }}</label>-->

                                        <!--<input type="file" name="audio_m" id="audio_m" class="form-control form-control-alternative{{ $errors->has('audio_m') ? ' is-invalid' : '' }}" value="" required>-->

                                        <!--@if ($errors->has('audio_m'))-->

                                        <!--    <span class="invalid-feedback" role="alert">-->

                                        <!--        <strong>{{ $errors->first('audio_m') }}</strong>-->

                                        <!--    </span>-->

                                        <!--@endif-->

                                        <!--</div> -->
                                        <!--<div class="col-md-6 form-group{{ $errors->has('audio_f') ? ' has-danger' : '' }}">-->

                                        <!--<label class="form-control-label" for="audio_f">{{ __('Audio Female') }}</label>-->

                                        <!--<input type="file" name="audio_f" id="audio_f" class="form-control form-control-alternative{{ $errors->has('audio_f') ? ' is-invalid' : '' }}" value="" required>-->

                                        <!--@if ($errors->has('audio_f'))-->

                                        <!--    <span class="invalid-feedback" role="alert">-->

                                        <!--        <strong>{{ $errors->first('audio_f') }}</strong>-->

                                        <!--    </span>-->

                                        <!--@endif-->

                                        <!--</div> -->
                                    </div>
                                    <hr>
                                    <div class="row" id="textfield">
                                    </div>
                                        <!-- <div class="col-md-6 form-group{{ $errors->has('text1') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="text1">{{ __('Text1') }}</label>

                                            <input type="text" name="text[]" id="text1" class="form-control form-control-alternative{{ $errors->has('text1') ? ' is-invalid' : '' }}" value="{{old('text1')}}" required>

                                            @if ($errors->has('text1'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('text1') }}</strong>

                                                </span>

                                            @endif

                                        </div>  -->
                                        <!-- <div class="col-md-11 form-group{{ $errors->has('text2') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="color">{{ __('Text2') }}</label>

                                            <input type="text" name="text[]" id="text2" class="form-control form-control-alternative{{ $errors->has('text2') ? ' is-invalid' : '' }}" value="{{old('text2')}}" required>

                                            @if ($errors->has('text2'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('text2') }}</strong>

                                                </span>

                                            @endif
                                           
                                        </div>
                                       
                                        <div class="col-md-1 d-flex align-items-center justify-content-center">
                                            <span id="addText" class="btn btn-success btn-md"><i class="fa-solid fa fa-plus"></i></span>
                                        </div>  -->

                                       
                                       
                                  

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
           $("#category").change(function () {
                var id = $('#category').val();
                var base_url='<?php echo env('BASE_URL')?>';
                $.ajax({

                    url: base_url+'subcategorylist/'+id,

                    type: 'get',

                    dataType: 'json',

                    success: function(response){

                        console.log(response);

                        if(response)

                        {
                            var result = response.data.subcategory_list;
                            var html='<option>----</option>';
                            for(var i=0;i<result.length;i++){
                                html += '<option value="'+result[i].id+'">'+result[i].name+'</option>'
                            }
                            $("#subcategory").html(html);
                            //alert('Status update successfully');

                        }else{

                            alert('Access denied');

                        }

                    }

                });
            });


            $("#primary_language").change(function () {
                var id = $('#primary_language').val();
                var base_url='<?php echo env('BASE_URL')?>';
                $.ajax({

                    url: base_url+'secondary_language_list/'+id,

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
                            $("#secondary_language").html(html);
                            //alert('Status update successfully');

                        }else{

                            alert('Access denied');

                        }

                    }

                });
            });

            $("#addText").click(function(e){
               var html='';
               html += '<div class="row">'+
                            '<div class="col-md-11 form-group{{ $errors->has('text1') ? ' has-danger' : '' }}">'+

                                '<label class="form-control-label" for="text1">{{ __('Text') }}</label>'+

                                '<input type="text" name="text[]" id="text1" class="form-control form-control-alternative{{ $errors->has('text1') ? ' is-invalid' : '' }}" value="{{old('text1')}}" required>'+

                                '@if ($errors->has('text1'))'+
                                    '<span class="invalid-feedback" role="alert">'+

                                        '<strong>{{ $errors->first('text1') }}</strong>'+

                                    '</span>'+

                                '@endif'+

                            '</div>'+ 
                            // '<div class="col-md-5 form-group{{ $errors->has('text2') ? ' has-danger' : '' }}">'+

                            // '<label class="form-control-label" for="color">{{ __('Text2') }}</label>'+

                            // '<input type="text" name="text[]" id="text2" class="form-control form-control-alternative{{ $errors->has('text2') ? ' is-invalid' : '' }}" value="{{old('text2')}}" required>'+

                            // '@if ($errors->has('text2'))'+

                            //     '<span class="invalid-feedback" role="alert">'+

                            //         '<strong>{{ $errors->first('text2') }}</strong>'+

                            //     '</span>'+

                            // '@endif'+

                            // '</div>'+
                           
                             '<div class="col-md-1 d-flex align-items-center justify-content-center">'+
                            '<span class="removeDiv btn btn-danger btn-md" onclick="removeDiv(this)"><i class="fa-solid fa fa-minus"></i></span>' +'</div>'+'</div>';
                $('#addHtml').append(html);
            });
           
            function removeDiv(obj){
               
                   $(obj).parent('div').parent('div').remove();
            }

            $('#template_type').change(function(){debugger;
               
                var template_type = $("#template_type option:selected").text();
                
                var lineno=template_type.split(' ');
                var html='';
                if(lineno[0]>0){
                    for(var index=1;index<=lineno[0];index++){
                        var text="Text"+index;
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
