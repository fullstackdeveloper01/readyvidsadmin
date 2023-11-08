@extends('layouts.app', ['title' => __('Video Details')])


@section('content')
   
 

    <div class="container-fluid mt-4">

        <div class="row">

            <div class="col-xl-12 video">
               
                <iframe src="{{ asset($video_url) }}" ></iframe>

              
           
            </div>
        
        </div>

      

      

    </div>
  
@endsection



