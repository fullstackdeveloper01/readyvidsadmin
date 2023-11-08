@extends('layouts.app', ['title' => __('Option Type Management')])



@section('content')

    @include('optiontype.partials.header', ['title' => __('Add Option Type')])



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

                                <a href="{{ route('optiontype.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>

                            </div>

                        </div>

                    </div>

                    <div class="card-body">

                        <!--<h6 class="heading-small text-muted mb-4">{{ __('template information') }}</h6>-->

                        <div class="pl-lg-4">

                            <form method="post" action="{{ route('optiontype.store') }}" autocomplete="off" enctype="multipart/form-data">

                                @csrf

                                </div>

                                <div class="pl-lg-4">

                                    <div class="row">

                                        <div class=" col-md-12 form-group{{ $errors->has('[type') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="type">{{ __('Type') }}</label>

                                            <input type="text" name="type" id="type" class="form-control form-control-alternative{{ $errors->has('type') ? ' is-invalid' : '' }}" placeholder="{{ __('Type') }}" value="{{old('type')}}" required>

                                            @if ($errors->has('type'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('type') }}</strong>

                                                </span>

                                            @endif

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

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

        <script type="text/javascript">

            $('.number').keyup(function(e) {

                if(this.value == 0) this.value =this.value.replace(/[^1-9\.]/g,'');
                else if(this.value < 0) this.value =this.value.replace(/[^0-9\.]/g,''); 
                else this.value =this.value.replace(/[^0-9\.]/g,'');               

                if(this.value.length > 2) { 
                    $(this).val($(this).attr('data-previous'));
                }else{
                    $(this).attr('data-previous',this.value);
                }
            });

            $('.hours').keyup(function(e) {
                if(this.value == 0) this.value =this.value.replace(/[^1-9\.]/g,'');
                else if(this.value < 0) this.value =this.value.replace(/[^0-9\.]/g,''); 
                else this.value =this.value.replace(/[^0-9\.]/g,'');               
            });

            $("#category_id").change(function () {
                var id = $('#category_id').val();
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
                            $("#subcategory_id").html(html);
                            //alert('Status update successfully');

                        }else{

                            alert('Access denied');

                        }

                    }

                });
            });

        </script>

        @include('layouts.footers.auth')

    </div>

@endsection

