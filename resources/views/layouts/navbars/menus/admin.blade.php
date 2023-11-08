<ul class="navbar-nav">

    <li class="nav-item">

        <a class="nav-link" href="{{ route('home') }}">

            <i class="ni ni-tv-2 text-pink"></i> {{ __('Dashboard') }}

        </a>

    </li>

    <li class="nav-item">

        <a class="nav-link" href="{{ route('users.index') }}">

            <i class="ni ni-single-02 text-pink"></i> {{ __('Users') }}

        </a>

    </li>

    

    

    <li class="nav-item">

        <a class="nav-link" href="{{route('video.index')}}">

            <i class="ni ni-album-2 text-pink"></i> {{ __('Videos') }}

        </a>

    </li>

    <li class="nav-item">

        <a class="nav-link" href="{{ route('template.index') }}">

            <i class="ni ni-collection text-pink"></i> {{ __('Templates') }}

        </a>

    </li>
     <li class="nav-item">

        <a class="nav-link" href="{{ route('quiz_video.index') }}">

            <i class="ni ni-collection text-pink"></i> {{ __('Quiz Video') }}

        </a>

    </li>

    <li class="nav-item">

        <a class="nav-link" href="{{ route('quiz_template.index') }}">

            <i class="ni ni-collection text-pink"></i> {{ __('Quiz Templates') }}

        </a>

    </li>

    <li class="nav-item">

        <a class="nav-link" href="{{ route('package.index') }}">

            <i class="ni ni-money-coins text-pink"></i> {{ __('Subscriptions') }}

        </a>

    </li>
    
    <li class="nav-item">

        <a class="nav-link" href="{{ route('discount.index') }}">

            <i class="ni ni-money-coins text-pink"></i> {{ __('Discounts') }}

        </a>

    </li>
    
     
    <li class="nav-item">

        <a class="nav-link" href="{{ route('contactus.index') }}">

            <i class="ni ni-money-coins text-pink"></i> {{ __('Contact Us') }}

        </a>

    </li>
    
    <li class="nav-item">

        <a class="nav-link" href="{{ route('affiliatepayment.index') }}">

            <i class="ni ni-money-coins text-pink"></i> {{ __('Affilaite Payment') }}

        </a>

    </li>
    
    <li class="nav-item">

        <a class="nav-link" href="{{ route('helpdesk.index') }}">

            <i class="ni ni-money-coins text-pink"></i> {{ __('Help Desk') }}

        </a>

    </li>
    
    
    
    <li class="nav-item">

        <a class="nav-link" href="#navbar-Reports" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-Master">

            <i class="ni ni-hat-3 text-pink"></i>

            <span class="nav-link-text">{{ __('Reports') }}</span>

        </a>

        <div class="collapse show" id="navbar-Reports">

            <ul class="nav nav-sm flex-column">

               

                <li class="nav-item">

                    <a class="nav-link" href="#">

                        <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('User Reports') }}

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link" href="#">

                        <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Payment Reports') }}

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link" href="#">

                        <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Top Category Reports') }}

                    </a>

                </li>

                

                <li class="nav-item">

                    <a class="nav-link" href="#">

                        <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Language Reports') }}

                    </a>

                </li>

          </ul>

        </div>

    </li>


    <li class="nav-item">

        <a class="nav-link" href="#">

            <i class="ni ni-credit-card text-pink"></i> {{ __('Refer & Earn') }}

        </a>

    </li>

    <li class="nav-item">

        <a class="nav-link" href="#navbar-Settings" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-Settings">

            <i class="ni ni-settings text-pink"></i> {{ __('Settings') }}

        </a>
        
       
        <div class="collapse show" id="navbar-Settings">

            <ul class="nav nav-sm flex-column">

            
                <li class="nav-item">

                    <a class="nav-link" href="#navbar-Master" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-Master">

                        <i class="ni ni-hat-3 text-pink"></i>

                        <span class="nav-link-text">{{ __('Master') }}</span>

                    </a>

                    <div class="collapse" id="navbar-Master">

                        <ul class="nav nav-sm flex-column">

                        

                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('languages.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Primary Language') }}

                                </a>

                            </li>
                            
                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('secondary_language.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Secondary Language') }}

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('categories.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Category') }}

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('subCategories.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('SubCategory') }}

                                </a>

                            </li>
                            
                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('ratio.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Ratio') }}

                                </a>

                            </li>
                              <li class="nav-item">

                                <a class="nav-link" href="{{ route('section.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Video Type') }}

                                </a>

                            </li>
                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('template_type.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Template Type') }}

                                </a>

                            </li>
                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('pattern.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Pattern') }}

                                </a>

                            </li>
                             <li class="nav-item">

                                <a class="nav-link" href="{{ route('talk_to_advisor.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Talk To Advisor') }}

                                </a>

                            </li>
                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('image.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Images') }}

                                </a>

                            </li>
                             <li class="nav-item">

                                <a class="nav-link" href="{{ route('voice.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Voice') }}

                                </a>

                            </li>

<!-- 
                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('aboutUs.index') }}">

                                    <img src="{{url('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('About Us') }}

                                </a>

                            </li> -->
                            
<!-- 
                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('termsCondition.index') }}">

                                    <img src="{{url('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Terms & Condition') }}

                                </a>

                            </li> -->

<!-- 
                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('policy.index') }}">

                                    <img src="{{url('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Privacy Policy') }}

                                </a>

                            </li> -->
<!-- 
                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('settings.index') }}">

                                    <img src="{{url('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Settings') }}

                                </a>

                            </li> -->

                        

                            

                            

                    </ul>

                    </div>

                </li>
            </ul>
            
        </div>
        
         <div class="collapse show" id="navbar-Settings">

            <ul class="nav nav-sm flex-column">


                <li class="nav-item">

                    <a class="nav-link" href="#navbar-Quiz-Master" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-Master">

                        <i class="ni ni-hat-3 text-pink"></i>

                        <span class="nav-link-text">{{ __('Quiz Master') }}</span>

                    </a>

                    <div class="collapse" id="navbar-Quiz-Master">

                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('video_size.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Video Size') }}

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('quiz_ratio.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Ratio') }}

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('country.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Country') }}

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('subjects.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Subject') }}

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('topics.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Topic') }}

                                </a>

                            </li>
                             <li class="nav-item">

                                <a class="nav-link" href="{{ route('templatetype.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Template Type') }}

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('optiontype.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Option Type') }}

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('quiz_pattern.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Pattern') }}

                                </a>

                            </li>
                            <li class="nav-item">

                                <a class="nav-link" href="{{ route('quiz_voice.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Voice') }}

                                </a>

                            </li>
                             <li class="nav-item">

                                <a class="nav-link" href="{{ route('question_answer_voice.index') }}">

                                    <img src="{{asset('/uploads/settings/more.png')}}" class="img-fluid w-10 mr-2"> {{ __('Question Answer Voice') }}

                                </a>

                            </li>

                           
                        </ul>

                    </div>

                </li>
            </ul>

        </div>
    

</ul>

