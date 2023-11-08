    @extends('layouts.app', ['title' => __('User Details')])



    @section('content')

        <style>

            .btn-toggle {

                margin: 0 auto;

                padding: 0;

                position: relative;

                border: none;

                height: 1.5rem;

                width: 3rem;

                border-radius: 1.5rem;

                color: #6b7381;

                background: #bdc1c8;

            }

            .btn-toggle:focus,

            .btn-toggle.focus,

            .btn-toggle:focus.active,

            .btn-toggle.focus.active {

                outline: none;

            }

            .btn-toggle:before,

            .btn-toggle:after {

                line-height: 1.5rem;

                width: 4rem;

                text-align: center;

                font-weight: 600;

                font-size: 8px;

                text-transform: uppercase;

                letter-spacing: 2px;

                position: absolute;

                bottom: 0;

                transition: opacity 0.25s;

            }

            .btn-toggle:before {

                content: '';

                left: -5rem;

            }

            .btn-toggle:after {

                content: '';

                right: -4rem;

                opacity: 0.5;   

            }

            .btn-toggle > .handle {

                position: absolute;

                top: 0.1875rem;

                left: 0.1875rem;

                width: 1.125rem;

                height: 1.125rem;

                border-radius: 1.125rem;

                background: #fff;

                transition: left 0.25s;

            }

            .btn-toggle.active {

                transition: background-color 0.25s;

                background-color: #29b5a8;

            }

            .btn-toggle.active > .handle {

                left: 1.6875rem;

                transition: left 0.25s;

            }

            .btn-toggle.active:before {

                opacity: 0.5;

            }

            .btn-toggle.active:after {

                opacity: 1;

            }

            

        </style>

        <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8 text-right">

            <div class="container-fluid">

                <a href="javascript:history.back()" class="btn btn-primary btn-md mr">Back</a>

            </div>

        </div>

        <div class="container-fluid mt--7">

            <div class="row">

                <div class="col-xl-9">

                    <br />

                    <div class="card bg-secondary shadow">

                        <div class="card-header bg-white border-0 bg-yellow-fade br-6 bordered">

                            <div class="row">

                                <div class="col-3">

                                    <h5 class="mb-0">Profile</h5>

                                    <h4 class="mb-0"><img src="{{url('/').'/'.$user->image}}" onerror="this.onerror=null;this.src='{{url("uploads/profile_pic/user.jpg")}}';" width="100" height="100"></h4>

                                </div>

                                <div class="col-3">

                                    

                                    <h5 class="mb-0">Name</h5>

                                    <h4 class="mb-0">{{$user['name']}}</h4>

                                </div>

                                <!-- <div class="col-3">

                                    <h4 class="mb-0">Last Login</h4>

                                    <h5>26 Oct 2021 14:18</h5>

                                </div> -->

                                <!-- <div class="col-3">

                                    <h4 class="mb-0">Package</h4>

                                    <h5 class="mb-0">@if($user->package_id!='0'){{$user->package_id}} @else NA @endif</h5>

                                </div> -->

                                <!-- <div class="col-3">

                                    <h4 class="mb-0">Mobile Number</h4>

                                    <h5 class="mb-0">{{$user->phone}}</h5>

                                </div> -->

                                <div class="col-3">

                                    <h4 class="mb-0">Email</h4>

                                    <h5 class="mb-0">@if($user->email) {{$user->email}} @else  - @endif</h5>

                                </div>

                                <!-- <div class="col-3">

                                    <h4 class="mb-0">Date of birth</h4>

                                    <h5 class="mb-0">@if($user->dob) {{$user->dob}} @else  - @endif</h5>

                                </div> -->

                                <div class="col-3">

                                    <h4 class="mb-0">Account Creation Date</h4>

                                    <h5>@if($user->created_at) {{$user->created_at->format('d M Y  H:i')}} @else  - @endif</h5>

                                </div>                                                                          

                            </div>

                        </div>

                    </div>

                    <!-- <br />

                    <div class="card card-profile shadow">

                        <div class="card-header">

                            <h6 class="heading-small text-muted mb-0">Business information</h6>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-lg-12">

                                    <p>

                                        @if($user->business_info) {{$user->business_info}} @else  - @endif

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div> -->

                    <br />

                    <div class="card card-profile shadow">

                        <div class="row">

                            <div class="col-lg-12">

                                <div class="card-header">

                                    <h6 class="heading-small text-muted mb-0">All Events</h6>

                                </div>

                                <div class="card-body">

                                    <div class="table-responsive">

                                        <table id="listing" class="table align-items-center table-flush">

                                            <thead class="thead-light">

                                            <tr>

                                                <th scope="col">{{ __('S.No') }}</th>

                                                <th scope="col">{{ __('User Name') }}</th>

                                                <th scope="col">{{ __('City') }}</th>

                                                <th scope="col">{{ __('Package type') }}</th>

                                                <th scope="col">{{ __('Hours') }}</th>

                                                <th scope="col">{{ __('Timing') }}</th>

                                                <th scope="col">{{ __('Party Type') }}</th>

                                                <th scope="col">{{ __('Status') }}</th>

                                                <th scope="col" class="text-right">Action</th>

                                            </tr>

                                            </thead>

                                            <tbody>

                                                @foreach ($event as $key=> $events)

                                                    <tr>

                                                        <td>{{ $key+1 }}</td>

                                                        <td>{{ App\Helpers\Helper::userName($events->user_id) }}</td>

                                                        <td>{{ App\Helpers\Helper::cityName($events->venue_city) }}</td>

                                                        <td>{{ App\Helpers\Helper::packageName($events->show_type) }}</td>

                                                        <td>{{ App\Helpers\Helper::packageTime($events->show_type) }}</td>

                                                        <td>{{ App\Helpers\Helper::getTime($events->party_time) }}</td>

                                                        <td>{{ App\Helpers\Helper::PartyName($events->party_type) }}</td>

                                                        <td>

                                                            @if($events->event_status==0)

                                                            {{'Pending'}}

                                                            @elseif($events->event_status==1)

                                                            {{'Complete'}}

                                                            @else

                                                            {{'Rejected'}}

                                                            @endif

                                                        </td>

                                                        <td class="text-right">

                                                            <a class="btn btn-info btn-sm" href="{{ route('event.show', $events) }}">{{ __('Show') }}</a>

                                                            <!-- <form action="{{ route('event.destroy', $events) }}" method="post">

                                                                @csrf

                                                                @method('delete')



                                                                <a class="dropdown-item" href="{{ route('event.edit', $events) }}">{{ __('Edit') }}</a>

                                                                @if($events->status==1)

                                                                <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to deactivate this event?") }}') ? this.parentElement.submit() : ''">

                                                                    {{ __('Deactivate') }}

                                                                </button>

                                                                @else

                                                                <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to activate this event?") }}') ? this.parentElement.submit() : ''">

                                                                    {{ __('Activate') }}

                                                                </button>

                                                                @endif

                                                            </form> -->

                                                        </td>

                                                    </tr>

                                                @endforeach

                                            </tbody>

                                        </table>

                                    </div>

                                    <div class="card-footer py-4">

                                    @if(count($event))

                                        <nav class="d-flex justify-content-end" aria-label="...">

                                            {{ $event->appends(Request::all())->links() }}

                                        </nav>

                                    @else

                                        <h4>{{ __('You don`t have any event') }} ...</h4>

                                    @endif

                                </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-xl-3 mb-5 mb-xl-0">

                    <br />

                    <div class="card card-profile shadow text-center">

                        <div class="card-header">

                            <h4 class="mb-0">Inactive / Active</h4>

                            @if($user->active==0)

                                <button type="button" onclick="changeUserStatus('{{$user->id}}')" class="btn btn-sm btn-toggle userStatus" data-toggle="button" aria-pressed="true" autocomplete="off">

                                    <div class="handle"></div>

                                </button>

                            @else

                                <button type="button" onclick="changeUserStatus('{{$user->id}}')" class="btn btn-sm btn-toggle active userStatus" data-toggle="button" aria-pressed="true" autocomplete="off">

                                    <div class="handle"></div>

                                </button>

                            @endif

                        </div>

                    </div>

                    <br />

                    <div class="card card-profile shadow">

                        <div class="row">

                            <div class="col-lg-12">

                                <div class="card-header">

                                    <h6 class="heading-small text-muted mb-0">Total Events</h6>

                                </div>

                                <div class="card-body">

                                    <div class="d-flex align-items-center">

                                        <i class="ni ni-money-coins text-orange mr-2"></i>

                                        <h4 class="mb-0">@if(isset($event)) {{count($event)}} @else 0 @endif</h4>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    

                </div>

            </div>

            <footer class="footer">

                <div class="row align-items-center justify-content-xl-between">                   

                </div>

            </footer>

            <!-- <div class="modal fade modal-xl" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">

                <div class="modal-dialog modal-l modal-dialog-centered" role="document">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h2 class="modal-title" id="modal-title-driver">Image</h2>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                <span aria-hidden="true">×</span>

                            </button>

                        </div>

                        <div class="modal-body">

                            <div class="row" id="image_row"></div>

                        </div>

                    </div>

                </div>

            </div> -->

        </div>

    @endsection

    

    

