@extends('layouts.app', ['title' => __('Dashboard')])



@section('content')

    @include('layouts.headers.cards')



    <div class="container-fluid mt--7">

        <div class="row">

            <div class="col-xl-5">

                <div class="card shadow">

                    <div class="card-header bg-transparent">

                        <div class="row align-items-center">

                            <div class="col-xl-8">

                                <h6 class="text-uppercase text-muted ls-1 mb-1"></h6>

                                <h2 class="mb-0">{{ __('Recent User') }}</h2>

                            </div>

                            <div class="col-xl-4">

                                <a href="{{route('users.index')}}">View All</a>

                            </div>

                        </div>

                    </div>

                    <div class="card-body" style="max-height: 600px;">

                        <div class="row">

                            <!-- @if(!$users->isEmpty())

                                @foreach($users as $key => $user)


                                   

                                    <div class="col-lg-9">

                                        <h3 >{{$user->name}}</h3>

                                        <p>{{ $user->email}}</p>
                                        <p>{{ $user->login_type}}</p>

                                    </div>

                                @endforeach

                            @endif -->
                            <div class="table-responsive">

                                <table class="table align-items-center table-flush shyamtrusttable" >

                                    <thead class="thead-light">

                                        <tr>

                                             <th scope="col">{{ __('ID') }}</th> 

                                            <th scope="col">{{ __('Name') }}</th>

                                            <th scope="col">{{ __('Email') }}</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        @foreach ($users as $user)

                                            @if($user->id > 1)

                                                <tr>

                                                     <td><a class="btn badge badge-success badge-pill" href="javascript:void(0)">{{ $user->id }}</a></td> 

                                                    <td>{{ $user->name }}</td>

                                                    <td>

                                                        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>

                                                    </td>
                                                  
                                                </tr>

                                            @endif

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <div class="col-xl-7 mb-5 mb-xl-0">

                <div class="card bg-gradient-default shadow">

                  

                    <div class="card-body">

                        <div id="columnchart_values" style="width:100%; height: 300px;"></div>

                        <!-- Chart -->

                       

                            <div class="chart">

                                <!-- Chart wrapper -->

                                <canvas id="chart-sales" class="chart-canvas"></canvas>

                            </div>

                     

                    </div>

                </div>

            </div>

            

        </div>





        @include('layouts.footers.auth')

    </div>

@endsection



@push('js')

    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>

    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <script type="text/javascript">

    google.charts.load("current", {packages:['corechart']});

    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = google.visualization.arrayToDataTable([

        ['Year', 'Visitations', { role: 'style' } ],

        ['2010', 10, 'color: #F15B26'],

        ['2020', 14, 'color: #F15B26'],

        ['2030', 16, 'color: #F15B26'],

        ['2040', 22, 'color: #F15B26'],

        ['2050', 28, 'stroke-color: #F15B26; stroke-opacity: 0.6; stroke-width: 8; fill-color: #BC5679; fill-opacity: 0.2']

      ]);



      var view = new google.visualization.DataView(data);

      view.setColumns([0, 1,

                       { calc: "stringify",

                         sourceColumn: 1,

                         type: "string",

                         role: "annotation" },

                       2]);



      var options = {

        title: "Monthly Income Chart",

        chartArea:{left:50,top:30,bottom:30,width:"100%",height:"100%"},

        titleTextStyle: {

                color: "#fff",               // color 'red' or '#cc00cc'

                fontSize: 25,               // 12, 18

                bold: true,                 // true or false

            },

        width:520,

        height: 500,

        backgroundColor: '#2e2b50',

        bar: {groupWidth: "50%"},

        legend: { position: "none" },

        hAxis: {textStyle:{color: '#FFF'} },

        vAxis: {textStyle:{color: '#FFF'} }

      };

      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));

      chart.draw(view, options);

  }

  </script>

@endpush

