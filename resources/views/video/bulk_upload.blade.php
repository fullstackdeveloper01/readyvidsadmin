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
                            <form method="post" action="{{ route('video.bulk_upload') }}" autocomplete="off" enctype="multipart/form-data">
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
                            var html='<option value=" ">----</option>';
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
    </script>
@endsection
