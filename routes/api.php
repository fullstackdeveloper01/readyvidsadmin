<?php
// -----------------------New apis For Ready Vids -----------------------------------
Route::post('register','ApiController@register');

Route::post('check_email','ApiController@checkEmail');

Route::post('login','ApiController@login');

//Route::post('google/login','ApiController@glogin');

Route::post('google/login','ApiController@googleLogin');

Route::post('forgotpassword','ApiController@forgotpassword');


Route::post('verifyotp','ApiController@verifyOtp');

Route::post('resetpassword','ApiController@resetPassword');

Route::get('packages','PackageController@getpackage');

Route::get('about_us','ApiController@about_us');

Route::get('term_condition','ApiController@term_condition');

Route::get('contact_support','ApiController@contact_support');
  
Route::get('privacy_policy','ApiController@policy');

Route::get('faq','ApiController@faq');



Route::post('packages_affiliate','ApiController@getPackageAffiliate');
    
Route::get('hour_time','ApiController@gethoursTime');

Route::post('city','ApiController@getCity');

Route::post('state','ApiController@getState');

Route::get('country_list','ApiController@getCountries');

Route::post('contact_us','ApiController@ContactUs');
 
Route::post('get_meta','ApiController@getMetadata');

Route::post('success','ApiController@successUrl');
        
Route::post('cancel','ApiController@cancelUrl');

 
Route::group(['middleware' => ['jwt.verify']], function() {
    
    Route::post('userdetails', 'ApiController@userdetails');
    
    Route::post('packages','ApiController@getpackage');
    
    Route::post('package_detail','ApiController@getpackageDetail');

    Route::post('image_upload','ApiController@imageUpload');

    Route::post('profile','ApiController@profileupdate');
    
    Route::post('logout', 'ApiController@logout');

    Route::post('add_team','ApiController@addTeam');

    Route::post('primary_languageList','ApiController@primaryLanguageList');

    Route::post('secondary_languageList','ApiController@secondaryLanguageList');
    
    Route::post('voice_list','ApiController@voiceList');

    Route::post('video_type_List','ApiController@videoTypeList');


    Route::post('ratio_List','ApiController@ratioList');

    Route::post('category_List','ApiController@categoryList');

    Route::post('subcategory_List','ApiController@subcategoryList');
    
    Route::post('template_get','ApiController@getTemplate');
     
    Route::post('templatetype_List','ApiController@templateTypeList');

    Route::post('template_List','ApiController@templateList');

    Route::post('video','ApiController@makeVideo');

    Route::post('myvideo','ApiController@myVideo');

    Route::post('talktoadvisor_List','ApiController@talkToAdvisorList');

    Route::post('final_video','ApiController@customizationVideo');
    
    Route::post('title','ApiController@saveTitle');
    
    Route::post('intro_video','ApiController@introVideo');
    
    Route::post('outro_video','ApiController@outroVideo');
    
    Route::post('main_video','ApiController@mainVideo');
    
    Route::post('watermark','ApiController@watermark');
    
    Route::post('watermark_video','ApiController@watermarkVideo');
    
    Route::post('finalvideo','UserApiController@finalVideo');
    
    
    Route::post('savefinalvideo','ApiController@saveFinalVideo');
    
    Route::post('savequizfinalvideo','ApiController@saveQuizFinalVideo');
    
    Route::post('download','ApiController@getDownload');
    
    Route::post('createOrder','ApiController@createOrder');
    
     Route::post('testingvideo','ApiController@makeVideonew');
    
    Route::post('removevideo','ApiController@removeVideo');
    
    Route::post('video_count','ApiController@videoCount');
     
    /****************Quiz module api*****************************/
    
    Route::post('video_size','ApiController@videoSizeList');
    
    Route::post('quiz_ratio_List','ApiController@quizRatioList');
    
    Route::post('country_List','ApiController@countryList');
    
    Route::post('subject_List','ApiController@subjectList');
    
    Route::post('topic_List','ApiController@topicList');
    
    
    Route::post('templatesdesign_List','ApiController@getQuizTemplate');
     
  

    Route::post('templatescolors_List','ApiController@quizTemplateList');
    
    Route::post('quiz_voice_list','ApiController@quizVoiceList');
    
    Route::post('quiz_video','ApiController@makeQuizVideo');
    
    Route::post('quiz_video_new','ApiController@makeQuizVideoNew');
      
    Route::post('myquizvideo','ApiController@myQuizVideo');
    
     Route::post('removequizvideo','ApiController@removeQuizVideo');
    
    Route::post('watermarkquizvideo','ApiController@watermarkQuizVideo');
    
    /******************************End Quiz Module Api*********************************/
    
    /*******************************Affilaite Module****************************************/
    
    
    Route::post('generate_link','ApiController@generateLink');
    
    Route::post('generate_link_list','ApiController@generateLinkList');
    
    Route::post('generate_link_delete','ApiController@generateLinkDelete');
    
    Route::post('generate_link_edit','ApiController@generateLinkEdit');
    
    Route::post('update_link','ApiController@generateLinkUpdate');
    
    Route::post('get_commission','ApiController@getCommission');
    
    Route::post('campaign_detail','ApiController@campaignDetail');
     
    Route::post('dashboard_value','ApiController@getDasboardValue');
    
    Route::post('commission_monthly','ApiController@getCommissionMonthlyWise');
      
    Route::post('packages_affiliate_upgrade','ApiController@getpackageUpgradeAffilate');
    
    Route::post('payment_applied','ApiController@paymentApplied');
    
    Route::post('help_desk','ApiController@helpDesk');
    
    Route::post('message_list','ApiController@messageList');
    
    Route::post('bank_detail_save','ApiController@saveBankDetail');
    
    Route::post('bank_detail','ApiController@getBankDetail');
    
    Route::post('package_history','ApiController@packageHistory');
    
    /**********************************************End Affiliate Module***********************************/
    
    
    /*******************************Payment Module****************************************/
    
    Route::post('payment','ApiController@payment');
    
    Route::post('retrievePaymentIntent','ApiController@retrievePaymentIntent');
    
    /**********************************************End Affiliate Module***********************************/
    
      /*******************************Business Partner Module****************************************/
    
    
    Route::post('monthly_list_business_partner','ApiController@getCommissionMonthlyList');
    
    Route::post('dashboard_value_business_partner','ApiController@getDasboardValueBusinessPartner');
    
    Route::post('user_monthly_list','ApiController@getUserListMonthlyWise');
    
    Route::post('commission_monthly_business_partner','ApiController@getCommissionMonthlyWiseBusinessPartner');
      
    //Route::post('packages_affiliate_upgrade','ApiController@getpackageUpgradeAffilate');
    
    //Route::post('payment_applied','ApiController@paymentApplied');
    
    
    /**********************************************End Business Partner Module***********************************/
        
});
