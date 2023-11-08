<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        <meta charset="UTF-8">

        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">



        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name="google-site-verification" content="FwJ-jLAn47p2xW_sLd6Oj83fthNudpn2THc2SPbuAYw" />

        <title>{{ config('app.name', 'Ready Vids') }}</title>

        <!-- Favicon -->

        <link href="{{ asset('argon') }}/img/brand/favicon.png" rel="icon" type="image/png">

        <!-- Fonts -->

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

        <!-- Icons -->

        <link href="{{ asset('argon') }}/vendor/nucleo/css/nucleo.css" rel="stylesheet">

        <link href="{{ asset('argon') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">

        <!-- Argon CSS -->

        <link type="text/css" href="{{ asset('argon') }}/css/argon.css?v=1.0.0" rel="stylesheet">

        <!-- Argon CSS -->

        <link type="text/css" href="{{ asset('custom') }}/css/custom.css" rel="stylesheet">

        <!-- Select2 -->

        <!--<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />-->

        <link type="text/css" href="{{ asset('custom') }}/css/select2.min.css" rel="stylesheet">

        <!-- Jasny File Upload -->

        <!--<link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" rel="stylesheet">-->

        <!-- Latest compiled and minified CSS -->

        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/4.0.0/css/jasny-bootstrap.min.css">

        <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css"> -->

        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->

        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">

        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.bootstrap4.min.css">

        <!-- Flatpickr datepicker -->

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

        @yield('head')

    </head>

    <body class="{{ $class ?? '' }}">

        @auth()

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">

                @csrf

            </form>

            @include('layouts.navbars.sidebar')

        @endauth

       

        @auth()

        <div class="main-content">

            @include('layouts.navbars.navbar')

            @yield('content')

        </div>

        @endauth

        

        @guest()

        <div class="main-content">

            @yield('content')

        </div>

        @endguest

       

        @guest()

            @include('layouts.footers.guest')

        @endguest



        <!-- Commented because navtabs includes same script -->

        <script src="{{ asset('argon') }}/vendor/jquery/dist/jquery.min.js"></script>



        <script src="{{ asset('argonfront') }}/js/core/popper.min.js" type="text/javascript"></script>

        <script src="{{ asset('argon') }}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>



        @stack('js')

        <!-- Navtabs -->

        <script src="{{ asset('argonfront') }}/js/core/jquery.min.js" type="text/javascript"></script>

        <!--<script src="{{ asset('argonfront') }}/js/core/bootstrap.min.js" type="text/javascript"></script>-->



        <script src="{{ asset('argon') }}/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>



        <!-- Nouslider -->

        <script src="{{ asset('argon') }}/vendor/nouislider/distribute/nouislider.min.js" type="text/javascript"></script>



        <!-- Argon JS -->

        <script src="{{ asset('argon') }}/js/argon.js?v=1.0.0"></script>



        <!-- Latest compiled and minified JavaScript -->

        <script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/4.0.0/js/jasny-bootstrap.min.js"></script>

        <!-- Custom js -->

        <script src="{{ asset('custom') }}/js/orders.js"></script>



        <script src="{{ asset('custom') }}/js/driver.js"></script>

         <!-- Custom js -->

        <script src="{{ asset('custom') }}/js/mresto.js"></script>

        <!-- AJAX -->

        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>-->

        <!-- SELECT2 -->

        <script src="{{ asset('custom') }}/js/select2.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>



        <!-- Google Map -->

        

<!-- <script type="text/javascript"  src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDwxPWJPMuhjqOXZXKF4oRS3v7ZKz_ULu0&sensor=false"></script> -->

<!-- <script type="text/javascript"  src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBV4vjijGNm3nEPqUjqgdkXZBgYK_cLQPs&sensor=false"></script> -->

<!-- 

        <script async defer src= "https://maps.googleapis.com/maps/api/js?libraries=geometry,drawing&key=<?php echo env('GOOGLE_MAPS_API_KEY',''); ?>">

        </script> -->

         <script src="{{ asset('custom') }}/js/rmap.js"></script>

         <!--< Import Vue >-->

        <script src="https://unpkg.com/vue@2.1.6/dist/vue.js"></script>

        <!-- Import AXIOS --->

        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

        <!-- Flatpickr datepicker -->

        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <!-- OneSignal -->

        <!-- <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script> -->

        <script>

             var ONESIGNAL_APP_ID = "{{ env('ONESIGNAL_APP_ID') }}";

             var USER_ID = '{{  auth()->user()?auth()->user()->id:"" }}';

        </script>

        <script src="{{ asset('custom') }}/js/onesignal.js"></script>





        <script>

            $( '.flatpickr' ).flatpickr({

            noCalendar: true,

            enableTime: true,

            dateFormat: 'h:i K'

        });



        

$(function(){

  

    /**topsubcategory */

    $('#parent_id').change(function(){

        var parent_id =$('#parent_id').val();

        // $.ajax({

        //     method: 'post',

        //     url: '/api/subcategory_list',

        //     data: {category_id:parent_id},

        // }).then(response => {

        //     if (response.status == true) {

        //         var result = response.data;

        //         var html='';

        //         for(var index=0;index<result.length;index++){

        //             html += '<option value="'+result[index].id+'">'+result[index].name+'</option>';

        //         }

        //         console.log(html);

        //         $('#topsubcategoryid').html(html);

        //     } else {

                

            

        //     }

            

        // }).catch(function (error) {

        //     console.log(error);

        // });



        $.ajax({

        url: '/subcategory_list/'+parent_id,

        type: 'get',

        dataType: 'json',

        success: function(response){

            console.log(response);

            if (response.status == true) {

                var result = response.data;

                var html='';

                for(var index=0;index<result.length;index++){

                    html += '<option value="'+result[index].id+'">'+result[index].name+'</option>';

                }

                $('#topsubcategoryid').html(html);

            } else {

                

            

            }

        }

    });

    });



    

});
function changeStatus(url,id,status){
    var base_url='<?php echo env('BASE_URL')?>';
    $.ajax({

        url: base_url+url+'/'+id+'/'+status,

        type: 'get',

        dataType: 'json',

        success: function(response){

            console.log(response);

            if(response)

            {

                alert('Status update successfully');

            }else{

                alert('Access denied');

            }

        }

    });
}
function changereviewStatus(id){

    if($('.valueStatus').hasClass('active')){

        var status=0;

    }else

    {

        var status=1;

    }

    $.ajax({

        url: '/reviewStatus/'+id+'/'+status,

        type: 'get',

        dataType: 'json',

        success: function(response){

            console.log(response);

            if(response)

            {

                alert('Status update successfully');

            }else{

                alert('Access denied');

            }

        }

    });

}



function changeUserStatus(id){

    if($('.userStatus').hasClass('active')){

        var status=0;

    }else

    {

        var status=1;

    }

    $.ajax({

        url: '/userStatus/'+id+'/'+status,

        type: 'get',

        dataType: 'json',

        success: function(response){

            console.log(response);

            if(response)

            {

                alert('Status update successfully');

            }else{

                alert('Access denied');

            }

        }

    });

}

</script> 

<script>

    $(function() {

        $( ".datepickerid" ).datepicker({

            format: 'dd-mm-yyyy'

        });



//$("#listing").datatable();

    });

</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('button[type="submit"]').click(function(){
            if($('input[name="country_name"]').val()==""){
                $('#country_error').html('Input Feild Required');
                return false;
            }else $('#country_error').html('');
        });

        $('button[type="submit"]').click(function(){
            if($('input[name="state_name"]').val()==""){
                $('#state_error').html('Input Feild Required');
                return false;
            }else $('#state_error').html('');
        });

        $('button[type="submit"]').click(function(){
            if($('input[name="city_name"]').val()==""){
                $('#city_error').html('Input Feild Required');
                return false;
            }else $('#city_error').html('');
        });

        $('button[type="submit"]').click(function(){
            if($('input[name="question"]').val()=="" && $('textarea[name="answer"]').val()==""){
                $('#question_error').html('Input Feild Required');
                $('#answer_error').html('Input Feild Required');
                return false;
            }else if($('input[name="question"]').val()==""){
                $('#question_error').html('Input Feild Required');
                return false;
            }else if($('textarea[name="answer"]').val()==""){
                $('#answer_error').html('Input Feild Required');
                return false;
            }else $('#question_error').html('');
        });

        $('button[type="submit"]').click(function(){
            if($('input[name="time"]').val()==""){
                $('#time_error').html('Input Feild Required');
                return false;
            }else $('#time_error').html('');
        });

        $('button[type="submit"]').click(function(){
            if($('input[name="type"]').val()==""){
                $('#type_error').html('Input Feild Required');
                return false;
            }else $('#type_error').html('');
        });
    });
</script>


<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

<script>

   // CKEDITOR.replace( 'shyamtrusteditor' );

</script>

<script>

    // CKEDITOR.replace( 'package_description' );

</script>

<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>



<script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>



 <script>

    $(document).ready(function() {

        $('#readyvidsdata').DataTable({
            order:[0,'desc']
        });

        $('#listing').DataTable( {

            fixedHeader: true,

            dom: 'Bfrtip',

            buttons: [

                // { extend: 'csv', className: 'btn btn-info',

                    // exportOptions: {

                    //     columns: [0,1,2,3,4,5,6,7,8]

                    // }

                // }

                // { extend: 'excel', className: 'btn btn-info' },

                

            ]

            

        });

        $('#birthday').DataTable();

    });

</script>

        @yield('js')

    </body>

</html>

