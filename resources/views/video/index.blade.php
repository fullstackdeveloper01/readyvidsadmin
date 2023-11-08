@extends('layouts.app', ['title' => __('Video')])

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h3 class="mb-0">{{ __('Video') }}</h3>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('video.create') }}" class="btn btn-sm btn-primary">{{ __('Add Video') }}</a>
                                 <a href="{{ route('video.bulk_upload') }}" class="btn btn-sm btn-primary">{{ __('Bulk Upload') }}</a>
                                 <!--<a href="{{ route('video.download') }}" class="btn btn-sm btn-primary">{{ __('Sample Download') }}</a>-->
                                <!--<a href="{{ route('video.create') }}" class="btn btn-sm btn-primary">{{ __('Make Sample Download') }}</a>-->
                                 <a href="{{ route('video.makesampledownload') }}" class="btn btn-sm btn-primary">{{ __('Make Sample Download') }}</a>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-8">
                                    <form method="get">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="form-group{{ $errors->has('parent_id') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="parent_id">{{ __('Category Name') }}</label>
                                                    <select name="category_id" id="category_id" class="form-control form-control-alternative{{ $errors->has('category_id') ? ' is-invalid' : '' }}" required>
                                                        <option value=""> -- </option>
                                                    @foreach($categoryList as $res)
                                                        <option value="{{$res->id}}" @if(isset($_GET['category_id']) && $_GET['category_id']==$res->id) selected @endif>{{$res->name}}</option>
                                                    @endforeach
                                                    
                                                    @if ($errors->has('category_id'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('category_id') }}</strong>
                                                        </span>
                                                    @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                 <div class="form-group{{ $errors->has('subcategory') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="subcategory">{{ __('SubCategory') }}</label>
                                                    <select name="subcategory_id" id="subcategory" class="form-control form-control-alternative{{ $errors->has('subcategory') ? ' is-invalid' : '' }}" >
                                                    <option value=""> -- </option>
                                                   
                                                    
                                                    @if ($errors->has('subcategory'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('subcategory') }}</strong>
                                                        </span>
                                                    @endif
                                              
                                                    </select>
                                                </div>
                                            </div>
                                             <div class="col-3">
                                                <div class="form-group{{ $errors->has('primary_language') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="primary_language">{{ __('Primary Language') }}</label>
                                                    <select name="primary_language" id="primary_language" class="form-control form-control-alternative{{ $errors->has('primary_language') ? ' is-invalid' : '' }}" >
                                                        <option value=""> -- </option>
                                                    @foreach($primaryLanguageList as $res)
                                                        <option value="{{$res->id}}" @if(isset($_GET['primary_language']) && $_GET['primary_language']==$res->id) selected @endif>{{$res->name}}</option>
                                                    @endforeach
                                                    
                                                    @if ($errors->has('primary_language'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('primary_language') }}</strong>
                                                        </span>
                                                    @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                 <div class="form-group{{ $errors->has('secondary_language') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="secondary_language">{{ __('Secondary Language') }}</label>
                                                    <select name="secondary_language" id="secondary_language" class="form-control form-control-alternative{{ $errors->has('secondary_language') ? ' is-invalid' : '' }}" >
                                                    <option value=""> -- </option>
                                                   
                                                    
                                                    @if ($errors->has('secondary_language'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('secondary_language') }}</strong>
                                                        </span>
                                                    @endif
                                              
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                   
                                </div>
                            <div class="col-4 text-right">   
                                @php
                                if(isset($_GET['category_id'])){
                                    $category_id=$_GET['category_id'];
                                }else{
                                    $category_id='';
                                }
                                if(isset($_GET['subcategory_id'])){
                                    $subcategory_id=$_GET['subcategory_id'];
                                }else{
                                    $subcategory_id='';
                                }
                                
                                if(isset($_GET['primary_language'])){
                                    $primary_language=$_GET['primary_language'];
                                }else{
                                    $primary_language='';
                                }
                                if(isset($_GET['secondary_language'])){
                                    $secondary_language=$_GET['secondary_language'];
                                }else{
                                    $secondary_language='';
                                }
                                
                                @endphp
                                <a class="btn btn-sm btn-primary" href="{{ route('video.export') }}?category_id=<?=$category_id?>&subcategory_id=<?=$subcategory_id?>&primary_language=<?=$primary_language?>&secondary_language=<?=$secondary_language?>">Export</a>
                                <button type="submit" class="btn btn-sm btn-primary">{{ __('Filter') }}</button>
                                <a href="{{ route('video.index') }}" class="btn btn-sm btn-primary">{{ __('Clear Filter') }}</a>
                            </div>
                             </form>
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
                                    <th scope="col">{{ __('Id') }}</th>
                                    <th scope="col">{{ __('Video Type') }}</th>
                                    <th scope="col">{{ __('Category') }}</th>
                                    <th scope="col">{{ __('Subcategory') }}</th>
                                    <th scope="col">{{ __('Text1') }}</th>
                                    <th scope="col">{{ __('Text2') }}</th>
                                    <th scope="col">{{ __('Image') }}</th>
                                    <th scope="col">{{ __('Audio Male') }}</th>
                                    <th scope="col">{{ __('Audio Female') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col" class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($videos  as $key=> $video)
                                    @php 
                                    $videotexts= App\VideoTextMapping::join('video_text','video_text_mapping.text_id','=','video_text.id')->where('video_text_mapping.video_id','=',$video->id)->get();
                                   // echo "<pre>";print_r($videotexts);die;
                                    @endphp
                                    <tr>
                                        <td><a href="{{ route('video.edit', $video) }}">{{ $key+1 }}</a></td>
                                        <td>{{$video->section_name}}</td>
                                        <td>{{$video->category_name}}</td>
                                        <td>{{$video->subcategory_name}}</td>
                                        <td>@if(count($videotexts)>0){{$videotexts[0]->text}}@endif</td>
                                        <td>@if(count($videotexts)>0){{$videotexts[1]->text}}@endif</td>
                                        <td>
                                            <img width="50px" alt="{{ $video->image }}" height="50px" src="{{ asset($video->image) }}">
                                        </td>
                                       
                                        <td>
                                            <audio controls="controls" src="{{ asset($video->audio_m) }}" type="audio/*">
                                            </audio>
                                        </td>
                                        <td>
                                            <audio controls src="{{ asset($video->audio_f) }}" type="audio/*">
                                            </audio>

                                        </td>
                                          @if($video->status==1)
                                         <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('videoStatus','{{$video->id}}','0')" checked >
                                                <div class="slider round"></div>
                                            </label>
                                        </td>
                                        @else
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" id="togBtn" onclick="changeStatus('videoStatus','{{$video->id}}','1')" >
                                                <div class="slider round"></div>
                                            </label>
                                        </td>
                                        @endif
                                        <!-- <td class="text-right">
                                            <form action="{{ route('video.destroy', $video) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="btn btn-danger" onclick="confirm('{{ __("Are you sure you want to delete this?") }}') ? this.parentElement.submit() : ''">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </td> -->
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                   
                                                    <a class="dropdown-item" href="{{ route('video.edit', $video) }}">{{ __('Edit') }}</a>
                                                    <form action="{{ route('video.destroy', $video) }}" method="post">
                                                        @csrf
                                                        @method('delete')

                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this video?") }}') ? this.parentElement.submit() : ''">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        @if(count($videos))
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $videos->appends(Request::all())->links() }}
                            </nav>
                        @else
                            <h4>{{ __('You don`t have any video') }} ...</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
       
        @include('layouts.footers.auth')
    </div>
     @php 
       
        if(isset($_GET['subcategory_id'])){
             $subcategory_id = $_GET['subcategory_id'];
        }else{
           $subcategory_id='';
        }
        if(isset($_GET['category_id'])){
             $category_id = $_GET['category_id'];
        }else{
           $category_id='';
        }
        
        if(isset($_GET['secondary_language'])){
             $secondary_language = $_GET['secondary_language'];
        }else{
           $secondary_language='';
        }
        if(isset($_GET['primary_language'])){
             $primary_language = $_GET['primary_language'];
        }else{
           $primary_language='';
        }
        
     @endphp
@endsection
@section('js')
<script>
    var subcategory_id="<?= $subcategory_id; ?>";
    var category_id="<?= $category_id; ?>";
    
    var secondary_language="<?= $secondary_language; ?>";
    var primary_language="<?= $primary_language; ?>";
    
    
    if(subcategory_id){
        var subcategoryselected='selected';
    }else{
       var subcategoryselected='';
    }
    var category_id = $("#category_id").val();
    if(category_id==''){
        category_id ='0';
    }
    
    
    if(secondary_language){
        var secondarylanguageselected='selected';
    }else{
       var secondarylanguageselected='';
    }
    var primary_language = $("#primary_language").val();
    if(primary_language==''){
        primary_language ='0';
    }
    
    
    
    if(category_id!=0){
     var base_url='<?php echo env('BASE_URL')?>';
    //$('#preview_name').html($("#name option:selected").text());
          $.ajax({
        
            method: 'get',
        
            url: base_url+'/subcategorylist/'+category_id,
        
          
        
        }).then(response => {
        
            if (response.status == true) {
        
                var result = response.data.subcategory_list;
              
                
                if(result.length>0){
                    var subcategoryhtml ='<option value=""></option>';
                     for(var counter=0;counter<result.length;counter++){
                        if(result[counter].id==subcategory_id){
                            subcategoryhtml +='<option value="'+result[counter].id+'"'+ subcategoryselected+'>'+result[counter].name+'</option>';
                        }else{
                            subcategoryhtml +='<option value="'+result[counter].id+'">'+result[counter].name+'</option>';
                        }
                    }
                    $("#subcategory").html(subcategoryhtml);
                }
               
            } else {
                   
                        $("#subcategory").html('<option value="">---------</option>'); 
              
            }
        
            
        
        }).catch(function (error) {
        
            console.log(error);
        
        });
    }
        
   $('#category_id').change(function(){
    
        var category_id = $("#category_id").val();
           if(category_id==''){
            category_id ='0';
        }
         var base_url='<?php echo env('BASE_URL')?>';
        //$('#preview_name').html($("#name option:selected").text());
          $.ajax({
    
            method: 'get',
    
            url: base_url+'/subcategorylist/'+category_id,
    
          
    
        }).then(response => {
    
            if (response.status == true) {
    
                var result = response.data.subcategory_list;
              
                
                if(result.length>0){
                    var subcategoryhtml ='<option value=""></option>';
                     for(var counter=0;counter<result.length;counter++){
                        subcategoryhtml +='<option value="'+result[counter].id+'">'+result[counter].name+'</option>';
                    }
                    $("#subcategory").html(subcategoryhtml);
                }
               
            } else {
                   
                        $("#subcategory").html('<option value="">---------</option>'); 
              
            }
    
            
    
        }).catch(function (error) {
    
            console.log(error);
    
        });
    });
    
    
    
    if(primary_language!=0){
     var base_url='<?php echo env('BASE_URL')?>';
    //$('#preview_name').html($("#name option:selected").text());
           $.ajax({
    
            method: 'get',
    
            url: base_url+'/secondary_language_list/'+primary_language,
    
          
    
        }).then(response => {
    
            if (response.status == true) {
    
                var result = response.data;
              
                
                if(result.length>0){
                    var secondarylanguagehtml ='<option value=""></option>';
                     for(var counter=0;counter<result.length;counter++){
                      
                         if(result[counter].id==secondary_language){
                            secondarylanguagehtml +='<option value="'+result[counter].id+'"'+ secondarylanguageselected+'>'+result[counter].name+'</option>';
                        }else{
                            secondarylanguagehtml +='<option value="'+result[counter].id+'">'+result[counter].name+'</option>';
                        }
                    }
                    $("#secondary_language").html(secondarylanguagehtml);
                }
               
            } else {
                   
                        $("#secondary_language").html('<option value="">---------</option>'); 
              
            }
    
            
    
        }).catch(function (error) {
    
            console.log(error);
    
        });
    }
    
    $('#primary_language').change(function(){
    
        var primary_language = $("#primary_language").val();
           if(primary_language==''){
            primary_language ='0';
        }
         var base_url='<?php echo env('BASE_URL')?>';
        //$('#preview_name').html($("#name option:selected").text());
          $.ajax({
    
            method: 'get',
    
            url: base_url+'/secondary_language_list/'+primary_language,
    
          
    
        }).then(response => {
    
            if (response.status == true) {
    
                var result = response.data;
              
                
                if(result.length>0){
                    var secondarylanguagehtml ='<option value=""></option>';
                     for(var counter=0;counter<result.length;counter++){
                        secondarylanguagehtml +='<option value="'+result[counter].id+'">'+result[counter].name+'</option>';
                    }
                    $("#secondary_language").html(secondarylanguagehtml);
                }
               
            } else {
                   
                        $("#secondary_language").html('<option value="">---------</option>'); 
              
            }
    
            
    
        }).catch(function (error) {
    
            console.log(error);
    
        });
    });
</script>
@endsection