@extends('layouts.app', ['title' => __('Template Type Management')])



@section('content')

    @include('template_type.partials.header', ['title' => __('Edit Template Type')])



    <div class="container-fluid mt--7">

        <div class="row">

            <div class="col-xl-12 order-xl-1">

                <div class="card bg-secondary shadow">

                    <div class="card-header bg-white border-0">

                        <div class="row align-items-center">

                            <div class="col-8">

                                <h3 class="mb-0">{{ __('Template Type Management') }}</h3>

                            </div>

                            <div class="col-4 text-right">

                                <a href="{{ route('template_type.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>

                            </div>

                        </div>

                    </div>

                    <div class="card-body">

                        <h6 class="heading-small text-muted mb-4">{{ __('Template Type information') }}</h6>

                        <div class="pl-lg-4">

                            <form method="post" action="{{ route('template_type.update',$template_type) }}" autocomplete="off" >

                                @csrf

                                @method('put')

                                </div>

                                <div class="pl-lg-4">

                                    <div class="row">

                                        <div class=" col-md-12 form-group{{ $errors->has('[type') ? ' has-danger' : '' }}">

                                            <label class="form-control-label" for="template_title">{{ __('Type') }}</label>

                                            <input type="text" name="type" id="type" class="form-control form-control-alternative{{ $errors->has('type') ? ' is-invalid' : '' }}" placeholder="{{ __('Type') }}" value="{{old('type')}}{{$template_type->type}}" required>

                                            @if ($errors->has('type'))

                                                <span class="invalid-feedback" role="alert">

                                                    <strong>{{ $errors->first('type') }}</strong>

                                                </span>

                                            @endif

                                        </div>
                                      
                                    </div>    
                                    

                                </div>
                                    <div class="text-center">

                                        <button type="submit" class="btn btn-success mt-4">{{ __('Update') }}</button>

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

        </script>

        @include('layouts.footers.auth')

    </div>

@endsection

