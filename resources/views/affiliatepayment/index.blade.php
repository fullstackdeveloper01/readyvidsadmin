@extends('layouts.app', ['title' => __('Affiliate Payment Management')])



@section('content')

    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">

    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">

    <div class="container-fluid mt--7">

        <div class="row">

            <div class="col">

                <div class="card shadow">

                    <div class="card-header border-0">

                        <div class="row align-items-center">

                            <div class="col-12">

                                <h3 class="mb-0">{{ __('Affiliate Payment List') }}</h3>
                                  
                                <form method="get">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group{{ $errors->has('role') ? ' has-danger' : '' }}">
                                                {{--
                                                <label class="form-control-label" for="role">{{ __('Login Type') }}</label>
                                                  
                                                <select name="role" id="role" class="form-control form-control-alternative{{ $errors->has('role') ? ' is-invalid' : '' }}" required>
                                                    <option value=""> -- </option>
                                                    <option value="4" @if(isset($_GET['role']) && $_GET['role']=="4") selected @endif>Affiliate payment </option>
                                                    <option value="2" @if(isset($_GET['role']) && $_GET['role']=="2") selected @endif>affilaitepayment </option>
                                                @if ($errors->has('role'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('role') }}</strong>
                                                    </span>
                                                @endif
                                                </select>
                                                --}}
                                            </div>
                                        </div>
                                        <div class="col-8 text-right">   
                                         {{--
                                            @php
                                            if(isset($_GET['role'])){
                                                $role=$_GET['role'];
                                            }else{
                                                $role='';
                                            }
                                          
                                            
                                            @endphp
                                           
                                            <button type="submit" class="btn btn-sm btn-primary">{{ __('Filter') }}</button>
                                            <a href="{{ route('affiliatepayment.index') }}" class="btn btn-sm btn-primary">{{ __('Clear Filter') }}</a>--}}
                                        </div>
                                     
                                           
                                    </div>
                                        
                                   
                                  
                                   
                                </form>
                       

                            </div>

                            {{--

                            <div class="col-4 text-right">

                                <a href="{{ route('affiliatepayment.create') }}" class="btn btn-sm btn-primary">{{ __('Add affilaitepayment') }}</a>

                            </div>

                            --}}

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

                        <table class="table align-items-center table-flush" id="readyvidsdata">

                            <thead class="thead-light">

                                <tr>

                                    <th scope="col">{{ __('ID') }}</th>

                                    <th scope="col">{{ __('Name') }}</th>

                                    <th scope="col">{{ __('Email') }}</th>

                                    <th scope="col">{{ __('Month Name') }}</th>

                                    <th scope="col">{{ __('Commission') }}</th> 

                                    <th scope="col">{{ __('Payment Status') }}</th>

                                    <th scope="col">{{ __('Payment Method') }}</th>
                                    
                                    <th scope="col">{{ __('Transaction') }}</th>
                                    
                                    <th scope="col">{{ __('Applied Date') }}</th>
                                     
                                    <th scope="col">{{ __('Payment Date') }}</th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($affiliatepayments as $affiliatepayment)

                                  

                                        <tr>

                                            <td><a class="btn badge badge-success badge-pill" href="{{route('affiliatepayment.payment', $affiliatepayment)}}">{{$affiliatepayment->id}}</a></td>

                                            <td>{{$affiliatepayment->name}}</td>

                                            <td>

                                                <a href="mailto:{{ $affiliatepayment->email }}">{{ $affiliatepayment->email }}</a>

                                            </td>
                                            
                                            <td>{{$affiliatepayment->month_name}}</td>
                                            <td>{{$affiliatepayment->commission}}</td>
                                            
                                            <td>{{$affiliatepayment->payment_status}}</td>
                                            
                                            <td>{{$affiliatepayment->payment_method}}</td>
                                            
                                            <td>{{$affiliatepayment->transaction}}</td>
                                            
                                            <td>{{date('Y-m-d',strtotime($affiliatepayment->created_at))}}</td>
                                            
                                            <td>@if($affiliatepayment->payment_date!=''){{date('Y-m-d',strtotime($affiliatepayment->payment_date))}}@endif</td>

                                        </tr>

                                  

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    

                </div>

            </div>

        </div>

        @include('layouts.footers.auth')

    </div>

@endsection