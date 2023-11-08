<?php



/*

|--------------------------------------------------------------------------

| Web Routes

|--------------------------------------------------------------------------

|

| Here is where you can register web routes for your application. These

| routes are loaded by the RouteServiceProvider within a group which

| contains the "web" middleware group. Now create something great!

|

*/



//Route::get('/', 'FrontEndController@index')->name('front');

Route::get('/'.env('URL_ROUTE','restaurant').'/{alias}', 'FrontEndController@restorant')->name('vendor');

Auth::routes();



Route::get('/', 'HomeController@index');
Route::get('/', 'HomeController@index')->name('home');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('voice/upload','CronController@uploadVoice')->name('voice.upload');

Route::get('question_answer_voice/upload','CronController@uploadQuestionAnswerVoice')->name('question_answer_voice.upload');

Route::group(['middleware' => 'auth'], function () {

    Route::resource('user', 'UserController');
    
    Route::get('/userApproved/{id}/{approved}','UserController@approved');

    Route::resource('categories', 'CategoriesController');

    Route::get('/categoryStatus/{id}/{status}','CategoriesController@status');

    Route::resource('subCategories', 'SubCategoriesController');

    Route::get('/subcategoryStatus/{id}/{status}','SubCategoriesController@status');


    //Route::resource('language', 'LanguageController');
    
    //Route::post('/status', 'LanguageController@status')->name('language.status');
    
   // Route::get('/languageStatus/{id}/{status}','LanguageController@status');

    
    
    Route::resource('secondary_language', 'SecondaryLanguageController');
    
    Route::get('/secondaryLanguageStatus/{id}/{status}','SecondaryLanguageController@status');
    
     Route::get('/secondary_language_list/{id}','SecondaryLanguageController@getSecondaryLanguageList');

     Route::get('/languageStatus/{id}/{status}','PrimaryLanguageController@status');

    Route::get('/primaryLanguageList/{id}','PrimaryLanguageController@primaryLanguageList');
    
     Route::resource('languages', 'PrimaryLanguageController');
    
   
    Route::resource('package', 'PackageController');

    Route::get('/packageStatus/{id}/{status}','PackageController@status');


    
    Route::resource('ratio', 'RatioController');

    Route::get('/ratioStatus/{id}/{status}','RatioController@status');
    
     Route::get('template/bulk_upload','TemplateController@bulk_upload')->name('template.bulk_upload');

    Route::post('template/bulk_upload','TemplateController@bulkStore')->name('template.bulk_upload');
    
    
    Route::get('template/makesampledownload', 'TemplateController@makeSampleDownload')->name('template.makesampledownload');

    Route::post('template/makesampledownload', 'TemplateController@makeSample')->name('template.makesampledownload');


    Route::resource('template', 'TemplateController');

    Route::get('/templateStatus/{id}/{status}','TemplateController@status');
    
  

    Route::get('/subcategorylist/{id}','SubCategoriesController@getSubCategoryList');


    Route::resource('section', 'SectionController');

    Route::get('/sectionStatus/{id}/{status}','SectionController@status');

    
      Route::get('video/bulk_upload','VideoController@bulk_upload')->name('video.bulk_upload');

    Route::post('video/bulk_upload','VideoController@bulkStore')->name('video.bulk_upload');
    
    Route::get('video/download', 'VideoController@getDownload')->name('video.download');
    
    Route::get('video/downloadvideo', 'VideoController@getDownloadVideo')->name('video.downloadvideo');
     
    Route::get('video/makesampledownload', 'VideoController@makeSampleDownload')->name('video.makesampledownload');

    Route::post('video/makesampledownload', 'VideoController@makeSample')->name('video.makesampledownload');
    
     Route::get('/videoStatus/{id}/{status}','VideoController@status');
    
      Route::get('video/export','VideoController@export')->name('video.export');   
      
    Route::resource('video', 'VideoController');

  
  

    Route::resource('template_type', 'TemplateTypeController');

    Route::get('/templatetypeStatus/{id}/{status}','TemplateTypeController@status');


    Route::resource('pattern', 'PatternController');

    Route::get('/patternStatus/{id}/{status}','PatternController@status');


     Route::get('/pattern/getpattern/{id}', 'PatternController@getPattern');


     Route::get('/pattern/gettemplatepattern/{id}/{id1}/{id2}/{id3}', 'PatternController@gettemplatepattern');



   Route::get('/talkToAdvisorStatus/{id}/{status}','TalkToAdvisorController@status');
    
    Route::resource('talk_to_advisor', 'TalkToAdvisorController');

    Route::get('image/files-upload', 'ImageController@filesUpload')->name('image.files_upload');
    
    Route::post('image/save-files-upload', 'ImageController@storeMultipleFile')->name('image.save_files_upload');

    Route::get('image/download', 'ImageController@getImageDownload')->name('image.download');

    Route::get('image/bulk_upload','ImageController@bulk_upload')->name('image.bulk_upload');

    Route::post('image/bulk_upload','ImageController@bulkStore')->name('image.bulk_upload');
    
    Route::post('image/make_folder','ImageController@makeFolder')->name('image.make_folder');
    
    Route::post('image/update_folder','ImageController@updateFolder')->name('image.update_folder');
    
    Route::post('image/delete_folder','ImageController@deleteFolder')->name('image.delete_folder');
    
    Route::get('image/export','ImageController@export')->name('image.export');

    Route::resource('image', 'ImageController');

    
    
    // Route::get('voice/files-upload', 'VoiceController@filesUpload')->name('voice.files_upload');
    
    // Route::post('voice/save-files-upload', 'VoiceController@storeMultipleFile')->name('voice.save_files_upload');


    
    Route::get('voice/download', 'VoiceController@getVoiceDownload')->name('voice.download');

    Route::get('voice/bulk_upload','VoiceController@bulk_upload')->name('voice.bulk_upload');

    Route::post('voice/bulk_upload','VoiceController@bulkStore')->name('voice.bulk_upload');
    
    Route::post('voice/make_folder','VoiceController@makeFolder')->name('voice.make_folder');
    
    Route::post('voice/update_folder','VoiceController@updateFolder')->name('voice.update_folder');
     
    Route::post('voice/delete_folder','VoiceController@deleteFolder')->name('voice.delete_folder');

    Route::get('voice/export','VoiceController@export')->name('voice.export');

   
    
    Route::resource('voice', 'VoiceController');

    Route::get('/subCategories/clone/{id}','SubCategoriesController@clone')->name('subCategories.clone');

    Route::post('/subCategories/clone/{id}','SubCategoriesController@cloneSubcategory')->name('subCategories.clone');


     Route::resource('discount', 'DiscountController');

    Route::get('/discountStatus/{id}/{status}','DiscountController@status');

    Route::resource('contactus', 'ContactController');



    Route::resource('affiliatepayment', 'AffiliatePaymentController');
    
    Route::get('payment/{id}','AffiliatePaymentController@payment')->name('affiliatepayment.payment');

    Route::resource('helpdesk', 'HelpDeskController');

    Route::post('reply','HelpDeskController@reply')->name('helpdesk.reply');


////////////////////////////////////////////////Quiz Module/////////////////////////////////////////////////


    
Route::resource('quiz_ratio', 'QuizRatioController');

Route::get('/quizratioStatus/{id}/{status}','QuizRatioController@status');

Route::resource('video_size', 'VideoSizeController');

Route::get('/videosizeStatus/{id}/{status}','VideoSizeController@status');

Route::resource('country', 'CountryController');

Route::get('/countryStatus/{id}/{status}','CountryController@status');


Route::resource('subjects', 'SubjectController');

Route::get('/subjectStatus/{id}/{status}','SubjectController@status');

Route::get('/subject_list/{id}','SubjectController@getSubjectList');


Route::resource('topics', 'TopicController');

Route::get('/topicStatus/{id}/{status}','TopicController@status');

Route::get('/topic_list/{id}/{id1}/{id2}','TopicController@getTopicList');

Route::resource('templatetype', 'QuizTemplateTypeController');

Route::get('/templatetypeStatus/{id}/{status}','QuizTemplateTypeController@status');

Route::resource('optiontype', 'OptionTypeController');

Route::get('/optiontypeStatus/{id}/{status}','OptionTypeController@status');


Route::resource('quiz_pattern', 'QuizPatternController');

Route::get('/quizPatternStatus/{id}/{status}','QuizPatternController@status');



Route::get('/quizpattern/getpattern/{id}', 'QuizPatternController@getPattern');


Route::get('/quizpattern/gettemplatepattern/{id}/{id1}/{id2}', 'QuizPatternController@gettemplatepattern');




Route::get('quiz_template/bulk_upload','QuizTemplateController@bulk_upload')->name('quiz_template.bulk_upload');

Route::post('quiz_template/bulk_upload','QuizTemplateController@bulkStore')->name('quiz_template.bulk_upload');

Route::get('quiz_template/makesampledownload', 'QuizTemplateController@makeSampleDownload')->name('quiz_template.makesampledownload');

Route::post('quiz_template/makesampledownload', 'QuizTemplateController@makeSample')->name('quiz_template.makesampledownload');

Route::resource('quiz_template', 'QuizTemplateController');

Route::get('/quizTemplateStatus/{id}/{status}','QuizTemplateController@status');






Route::get('quiz_video/bulk_upload','QuizVideoController@bulk_upload')->name('quiz_video.bulk_upload');

Route::post('quiz_video/bulk_upload','QuizVideoController@bulkStore')->name('quiz_video.bulk_upload');

Route::get('quiz_video/download', 'QuizVideoController@getDownload')->name('quiz_video.download');

Route::get('quiz_video/downloadvideo', 'QuizVideoController@getDownloadVideo')->name('quiz_video.downloadvideo');
 
Route::get('quiz_video/makesampledownload', 'QuizVideoController@makeSampleDownload')->name('quiz_video.makesampledownload');

Route::post('quiz_video/makesampledownload', 'QuizVideoController@makeSample')->name('quiz_video.makesampledownload');

 Route::get('/quizVideoStatus/{id}/{status}','QuizVideoController@status');

Route::get('quiz_video/export','QuizVideoController@export')->name('quiz_video.export');   
  
Route::resource('quiz_video', 'QuizVideoController');





 Route::get('/quizVoiceStatus/{id}/{status}','QuizVoiceController@status');

    
  
Route::resource('quiz_voice', 'QuizVoiceController');



Route::get('/topic/clone/{id}','TopicController@clone')->name('topics.clone');

Route::post('/topic/clone/{id}','TopicController@cloneTopic')->name('topics.clone');





Route::get('question_answer_voice/download', 'QuestionAnswerVoiceController@getVoiceDownload')->name('question_answer_voice.download');

Route::get('question_answer_voice/bulk_upload','QuestionAnswerVoiceController@bulk_upload')->name('question_answer_voice.bulk_upload');

Route::post('question_answer_voice/bulk_upload','QuestionAnswerVoiceController@bulkStore')->name('question_answer_voice.bulk_upload');

Route::post('question_answer_voice/make_folder','QuestionAnswerVoiceController@makeFolder')->name('question_answer_voice.make_folder');

Route::get('question_answer_voice/export','QuestionAnswerVoiceController@export')->name('question_answer_voice.export');

Route::post('question_answer_voice/update_folder','QuestionAnswerVoiceController@updateFolder')->name('question_answer_voice.update_folder');


Route::post('question_answer_voice/delete_folder','QuestionAnswerVoiceController@deleteFolder')->name('question_answer_voice.delete_folder');




Route::resource('question_answer_voice', 'QuestionAnswerVoiceController');

/*****************************************************end Quiz ******************************************/










    Route::get('user-review/{id}', 'UserController@review')->name('user.review');

    Route::get('/reviewStatus/{id}/{status}','UserController@reviewStatus');



    Route::resource('users', 'UserController');



    Route::get('/users/details', 'UserController@details')->name('details');

    

    Route::resource('country', 'CountryController');

    Route::get('getCountry/{id?}', 'CountryController@getCountry')->name('getCountry');

    

    Route::resource('state', 'StateController');

    Route::get('getState/{id?}', 'StateController@getState')->name('getState');

    

    Route::resource('city', 'CityController');

    Route::get('getCity', 'CityController@getCity')->name('getCity');





    Route::resource('gallery', 'GalleryController');

    




    



    



 



    Route::resource('clients', 'ClientController');

    

    Route::resource('aboutUs', 'AboutUsController');

    // Route::put('aboutUs-update/{id}', 'AboutUsController@update')->name('aboutUs.update');

    

    Route::resource('termsCondition', 'TermsConditionController');



    Route::resource('policy', 'PolicyController');

    

    Route::resource('settings', 'SettingsController');



	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);

	

    Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);


    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);



    Route::resource('addresses', 'AddressControler');

  

    Route::get('/new/address/autocomplete','AddressControler@newAddressAutocomplete');

    Route::post('/new/address/details','AddressControler@newAdressPlaceDetails');



    Route::post('/change/{page}', 'PagesController@change')->name('changes');



    Route::post('ckeditor/image_upload', 'CKEditorController@upload')->name('upload');




});



  Route::get('downloadvideo', 'FrontEndController@getDownload')->name('downloadvideo');

Route::get('/footer-pages', 'PagesController@getPages');




Route::resource('pages', 'PagesController');



Route::get('/login/google', 'Auth\LoginController@googleRedirectToProvider')->name('google.login');

Route::get('/login/google/redirect', 'Auth\LoginController@googleHandleProviderCallback');



Route::get('/login/facebook', 'Auth\LoginController@facebookRedirectToProvider')->name('facebook.login');

Route::get('/login/facebook/redirect', 'Auth\LoginController@facebookHandleProviderCallback');


/** listing status*/





Route::get('/userStatus/{id}/{status}','UserController@status');