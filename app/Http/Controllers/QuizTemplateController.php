<?php

namespace App\Http\Controllers;

use App\QuizTemplate;
use App\QuizTemplateType;
use App\QuizPattern;
use App\Video;
use App\OptionType;
use App\VideoText;
use App\Topic;
use App\QuizRatio;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\DriverCreated;
use Symfony\Component\Process\Process;
use spipu\html2pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use Image;
use Auth;
class QuizTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates  = QuizTemplate::where("status","=","1")->orderBy('quiz_templates.id','desc')->paginate(10);
        return view('quiz_template.index',compact('templates') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->hasRole('admin')){
           $typeList =QuizTemplateType:: where('status','=',1)->get();
            $patternList =QuizPattern:: where('status','=',1)->get();
            $ratioList =QuizRatio:: where('status','=',1)->get();
            $optionList =OptionType:: where('status','=',1)->get();
            return view('quiz_template.create',['typeList' =>$typeList,'patternList' =>$patternList,'ratioList' =>$ratioList,'optionList' =>$optionList]);
           
        }else return redirect()->route('quiz_template.index')->withStatus(__('No Access'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
        //Validate
        $request->validate([
            'name' => ['required'],
            'template_name' => ['required'],
            'ratio' => ['required'],
            'option_type_id' => ['required'],
            'topic_id' => ['required'],
            'pattern' => ['required'],
            'answerbackgroundcolor' => ['required'],
            'template_html' => ['required'],
            
        ]);
        


        $template = new QuizTemplate;
       
        $template->name = $request->name;
        $template->pattern = $request->pattern;
        
        $template->ratio = $request->ratio;
        
        $template->backgroundcolor = $request->backgroundcolor;
        $template->answerbackgroundcolor = $request->answerbackgroundcolor;

        $template->question_fonts = $request->question_fonts;
        $template->question_fontcolor = $request->question_color;
        $template->question_fontbackgroundcolor = $request->question_textbgcolor;
        
        
        $template->question_bordercolor = $request->question_bordercolor;

        $template->option_font = $request->option_fonts;
        $template->option_color = $request->option_color;
        $template->option_textbgcolor = $request->option_textbgcolor;
        
        
        $template->option_bordercolor = $request->option_bordercolor;
        
        
        $template->optionfonts = $request->optionfonts;
        $template->optioncolor = $request->optioncolor;
        $template->optiontextbgcolor = $request->optiontextbgcolor;
        
        

        //$template->template_image = $request->template_image;  
        $template->template_name = $request->template_name;
        $template->template_html = preg_replace('~>\s+<~', '><', $request->template_html);
        $template->template_html_string = preg_replace('~>\s+<~', '><', $request->template_html_string);

        $template->option_type_id = $request->option_type_id;
      
        $template->topic_id = implode(',',$request->topic_id);
                      
        /*make image*/
        /*
        if ($request->hasFile('image')) {
           
            $extension = $request->file('image')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->image->move(public_path('uploads/quiz_template_image'), $fileNameToStore);
            $template->image = 'uploads/quiz_template_image/'.$fileNameToStore;
               
        }
        */
        $html_string=preg_replace('~>\s+<~', '><', $request->template_html);
        
         
        if (strpos($html_string,'{question_number}') !== false) {
            $html_string= str_replace('{question_number}','',$html_string);
        } 
        if (strpos($html_string,"{openbody}") !== false) {
           $html_string= str_replace('{openbody}',"<body style='",$html_string);
        }
        
        if (strpos($html_string,"{closebody}") !== false) {
           $html_string= str_replace('{closebody}',"'>",$html_string);
        }
        
        if (strpos($html_string,"{endbody}") !== false) {
           $html_string= str_replace('{endbody}',"</body>",$html_string);
        }
        
        if(isset($request->question_fonts)){
            $fonts_array= explode(':',$request->question_fonts);
            $fontstyle= explode(' ',$fonts_array[1]);
            
            $style ='font-family:'.$fonts_array[0];
            $style .=';font-weight:'.$fontstyle[0];
            if(count($fontstyle)>1){
                $style .=';font-style:'.$fontstyle[1];
            }
    
            if (strpos($html_string,'{font_family}') !== false) {
                $html_string= str_replace('{font_family}',$style,$html_string);
            }
        }
        
        if (strpos($html_string,'{question_fontcolor}') !== false) {
            $html_string= str_replace('{question_fontcolor}',$request->question_color,$html_string);
        }
        
        if (strpos($html_string,'{question_fontbackgroundcolor}') !== false) {
            $html_string= str_replace('{question_fontbackgroundcolor}',$request->question_textbgcolor,$html_string);
        }
        
        if (strpos($html_string,'{question_border}') !== false) {
            $html_string= str_replace('{question_border}',$request->question_bordercolor,$html_string);
        }
                    
        if(isset($request->optionfonts)){
            $optionfonts_array= explode(':',$request->optionfonts);
            $optionfontstyle= explode(' ',$optionfonts_array[1]);
            
            $optionstyle ='font-family:'.$optionfonts_array[0];
            $optionstyle .=';font-weight:'.$optionfontstyle[0];
            if(count($optionfontstyle)>1){
                $optionstyle .=';font-style:'.$optionfontstyle[1];
            }
    
            if (strpos($html_string,'{optionfontfamily}') !== false) {
                $html_string= str_replace('{optionfontfamily}',$optionstyle,$html_string);
            }
                    
        }
        
        if (strpos($html_string,'{optionfontcolor}') !== false) {
            $html_string= str_replace('{optionfontcolor}',$request->optioncolor,$html_string);
        }
        
        if (strpos($html_string,'{optionfontbackgroundcolor}') !== false) {
            $html_string= str_replace('{optionfontbackgroundcolor}',$request->optiontextbgcolor,$html_string);
        }
        if(isset($request->option_fonts)){
            $optionfonts_array= explode(':',$request->option_fonts);
            $optionfontstyle= explode(' ',$optionfonts_array[1]);
            
            $optionstyle ='font-family:'.$optionfonts_array[0];
            $optionstyle .=';font-weight:'.$optionfontstyle[0];
            if(count($optionfontstyle)>1){
                $optionstyle .=';font-style:'.$optionfontstyle[1];
            }
    
            if (strpos($html_string,'{option_font_family}') !== false) {
                $html_string= str_replace('{option_font_family}',$optionstyle,$html_string);
            }
                    
        }
        
        if (strpos($html_string,'{option_fontcolor}') !== false) {
            $html_string= str_replace('{option_fontcolor}',$request->option_color,$html_string);
        }
       
        if (strpos($html_string,'{option_fontbackgroundcolor}') !== false) {
            $html_string= str_replace('{option_fontbackgroundcolor}',$request->option_textbgcolor,$html_string);
        }
         
        if (strpos($html_string,'{answer_border}') !== false) {
            $html_string= str_replace('{answer_border}',$request->option_bordercolor,$html_string);
        }
        //echo $html_string;die;
        $htmlpath = "uploads/quiz_temp_html/".Auth::user()->id;
        $html_url = $htmlpath."/".time().'.html';
        $html_path=public_path()."/".$html_url;
        $destinationHtmlPath = public_path($htmlpath);   
        if( !is_dir( $destinationHtmlPath ) ) mkdir( $destinationHtmlPath, 0755, true );
        
        file_put_contents($html_path, $html_string);
        
        
        $htmlFile = $html_path;
            
        $imagepath = "uploads/quiz_template_image/".Auth::user()->id;
        $image_url = $imagepath."/".time().'.jpg';
        $image_path=public_path()."/".$image_url;
        $destinationPath = public_path($imagepath);   
        if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
        
        
        $outputImage = $image_path;
        
         $result = QuizRatio::where('id','=',$request->ratio)->first();
             
        if(!empty($result) && $result->value=="16:9"){
            $command = "php artisan convert:html-to-image-template $htmlFile $outputImage";
        }
        if(!empty($result) && $result->value=="9:16"){
             $command = "php artisan convert:html-to-image-vertical-template $htmlFile $outputImage";
        }
        $output = shell_exec($command);
             
             
                            
/*        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml( $html_string); 
        
        $ratio= QuizRatio::where("id",'=',$request->ratio)->first();
        if($ratio!=null){
            if(!empty($ratio->value) && $ratio->value=="16:9"){
                $customPaper = array(0,0,1440,1024);
            }
            if(!empty($ratio->value) && $ratio->value=="9:16"){
                  $customPaper = array(0,0,600,800);
            }
        }
        
        //$customPaper = array(0,0,1440,1024); 
        // (Optional) Setup the paper size and orientation
       // $customPaper = array(0,0,612,612);
        // $customPaper = array(0,0,2048,1152);
        // $customPaper = array(0,0,2048,1152);
        $dompdf->setPaper($customPaper);
        $dompdf->curlAllowUnsafeSslRequests = true;
        

        
        // Render the HTML as PDF
        $dompdf->render();
        
        $pdfpath = "uploads/quiz_temp_pdf/".Auth::user()->id;
        $pdf_url = $pdfpath."/".time().'.pdf';
        $pdf_path=public_path()."/".$pdf_url;
        $destinationpdfPath = public_path($pdfpath);   
        if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
        
        file_put_contents($pdf_path, $dompdf->output());
        
        $imagick = new \Imagick();
        $imagick->readImage($pdf_path);
        $imagepath = "uploads/quiz_template_image/".Auth::user()->id;
        $image_url = $imagepath."/".time().'.jpg';
        $image_path=public_path()."/".$image_url;
        $destinationPath = public_path($imagepath);   
        if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
        
        $imagick->writeImages($image_path, true);*/
        
        if(file_exists($image_path)) { 
            $template->template_image =env("APP_URL").'public/'.$image_url;
             $template->image =$image_url;
        }
             
         // dd($template);     
        $template->save();
      
        return redirect()->route('quiz_template.index')->withStatus(__('Template successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     echo $id;die;
    //     //
    // }
    
        public function show($id)
    {
        $template = Template::findorfail($id);
        $html = $template->template_html_string;
        $videos= Video::select('video.*','templates_type.type as templatetype')->join('templates_type','video.template_type','=','templates_type.id')->orderBy('id','desc')->inRandomOrder()->limit(5)->get();
        
        $txtfile = fopen(public_path()."/input.txt", "w") or die("Unable to open file!");
        $soundfile = fopen(public_path()."/input1.txt", "w") or die("Unable to open file!");
        $blanksoundfile = fopen(public_path()."/input2.txt", "w") or die("Unable to open file!");
        foreach($videos as $video){
            //dd($video);
            $video_text = VideoText::where('video_id','=',$video->id)->get();
            $video_html = $template->template_html_string;
            $template_type= explode(' ',$video->templatetype);
            $lineno = $template_type[0];
            //for($counter=1;$counter<=$lineno;$counter++){
            foreach($video_text as $key=>$text){
                $counter= $key+1;
               
                $searchtext = '{text'.$counter.'}';
                if (strpos($video_html,$searchtext) !== false) {
                   $video_html= str_replace($searchtext,$text['text'],$video_html);
                }
            }
            
            if (strpos($video_html,"{img}") !== false) {
                if(!empty($video->thumbnail_600_500)){
                      $path = config('app.asset_url').'/'.$video->thumbnail_600_500;
                }else{
                      $path = config('app.asset_url').'/'.$video->image;
                }
                
              
                $video_html= str_replace('{img}',$path,$video_html);
             }
            // echo $video_html;die;
              // $video->video_html=$video_html;
              
            $URL= env('APP_URL').'testhtml.html';
          // echo $contents =file_get_contents($URL);die;
            $c = curl_init();
            curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($c, CURLOPT_URL, $URL);
            $contents = curl_exec($c);
            curl_close($c);
            
            if (strpos($contents,"{html}") !== false) {
                $path = config('app.asset_url').'/'.$video->image;
                $contents= str_replace('{html}',$video_html,$contents);
            }
           //echo $contents;die;
            $options = new Options();
            $options->setIsRemoteEnabled(true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml( $contents); 
             
             // (Optional) Setup the paper size and orientation
            //  $customPaper = array(0,0,200,200);
            // // $customPaper = array(0,0,2048,1152);
            // // $customPaper = array(0,0,2048,1152);
            //  $dompdf->setPaper($customPaper);
             $dompdf->curlAllowUnsafeSslRequests = true;
             

             
             // Render the HTML as PDF
             $dompdf->render();
             $pdfname= 'Brochure.pdf';
             file_put_contents($pdfname, $dompdf->output());
            
             $imagick = new \Imagick();
            $imagick->readImage($pdfname);
            $imagepath = "uploads/temp_image/".time().'.jpg';
            $image_path=public_path()."/".$imagepath;
            //$imagick->writeImages($image_path, true);
            $imagick->writeImages('test.jpg', true);
            die; 
            $audio_path = public_path().'/'.$video->audio_m;
            //echo "ffprobe -i $audio_path";die;
            $duration = shell_exec("ffprobe -i $audio_path -show_entries format=duration");
            $output_array = explode('=',$duration);
            $duration_array= explode('[/FORMAT]',$output_array[1]);

            $duration = (float)$duration_array[0];
          
                
            $txt = "file ".$imagepath."\n";
            fwrite($txtfile, $txt);
            $txt = "duration ".$duration." \n";
            fwrite($txtfile, $txt);
          
            
            $txt = "file ".$video->audio_m." \n";
            fwrite($soundfile, $txt);
            $txt = "outpoint ".$duration." \n";
            fwrite($soundfile, $txt);
            sleep(2);
            
            // $txt = "file 1-second-of-silence.mp3 \n";
            // fwrite($blanksoundfile, $txt);
            // $txt = "outpoint ".$newDuration." \n";
            // fwrite($blanksoundfile, $txt);
            
          
           
        }
        fclose($txtfile);

        fclose($soundfile);
       
        fclose($blanksoundfile);

        $file_path = public_path().'/input.txt';
        $file_path1 = public_path().'/input1.txt';
        $file_path2 = public_path().'/input2.txt';
        
        //  $file_path = env('APP_URL').'public/input.txt';
        // $file_path1 = env('APP_URL').'public/input1.txt';
        
        $video_url = "/uploads/make_video/".time().".mp4";
        $output_path= public_path().$video_url;
        
        $ratio=" -r 15 -aspect 16:9 -strict -2 ";
        // echo "ffmpeg -f concat -i $file_path -f concat -i $file_path1 -r 25 -pix_fmt yuv420p  $ratio $output_path 2>&1";die;
        $output= shell_exec("ffmpeg -f concat -i $file_path -f concat -i $file_path1 -r 25 -pix_fmt yuv420p  $ratio $output_path 2>&1");
        // $output= shell_exec("ffmpeg -f concat -i $file_path -f concat -i $file_path1 -f concat -i $file_path2 -r 25 -pix_fmt yuv420p   $output_path 2>&1");
        // $output= shell_exec("ffmpeg -f concat -i $file_path -f concat -i $file_path1 -r 25 -pix_fmt yuv420p   $output_path 2>&1");
        // echo "<pre>";
        // print_r($output);die;
       
        return view('quiz_template.show',compact('video_url'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(Template $template)
    {
       
        $typeList =TemplateType:: where('status','=',1)->get();
        $patternList =Pattern:: where('status','=',1)->get();
        $ratioList =Ratio:: where('status','=',1)->get();
       
        return view('quiz_template.edit', compact('template','typeList','patternList','ratioList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
   public function update(Request $request, Template $template)
    {
        $request->validate([
            'name' => ['required'],
            'type' => ['required'],
            'pattern' => ['required'],
            'backgroundcolor' => ['required'],
            'fonts' => ['required'],
            'color' => ['required'],
            'text' => ['required', 'string', 'max:255'],
        ]);
      
        //$template = new Template;
        $template->name = $request->name;
        $template->type = $request->type;
        $template->pattern = $request->pattern;
        $template->backgroundcolor = $request->backgroundcolor;
        $template->text = $request->text;
        $template->fonts = $request->fonts;
        $template->color = $request->color;
      
        // if ($request->hasFile('image')) {
        //     $filenameWithExt = $request->file('image')->getClientOriginalName ();
        //     // Get just Extension
        //     $extension = $request->file('image')->getClientOriginalExtension();
        //     // Filename To store
        //     $fileNameToStore = time().'.'.$extension;

        //     $request->image->move(public_path('uploads/template'), $fileNameToStore);
        //     $template->image = 'uploads/template/'.$fileNameToStore;
        // }
        // else {
        //     $fileNameToStore = 'No-image.jpeg';
        // }
       

        $template->update();
        return redirect()->route('quiz_template.index')->withStatus(__('Template successfully updated.'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuizTemplate $quiz_templates,$id)
    {
        $affectedRows =  QuizTemplate::where('id','=',$id)->delete();
        return redirect()->route('quiz_template.index')->withStatus(__('Template successfully deleted.'));
      
        
    }

   
    public function gettemplate(){
 
        return response()->json([
            'data' =>template::where(['active'=>1])->get(),
            'status' => true,
            'errMsg' => ''
        ]);
    }

    public function status($id,$status)
    {  
        $template = template::findorfail($id);
        $template->active=$status;
        $template->update();
        echo true;
        
    }
    
    public function makeSampleDownload(){
        if(auth()->user()->hasRole('admin')){
            $typeList =QuizTemplateType:: where('status','=',1)->get();
            $patternList =QuizPattern:: where('status','=',1)->get();
            $ratioList =QuizRatio:: where('status','=',1)->get();
            $optionList =OptionType:: where('status','=',1)->get();
            return view('quiz_template.makesampledownload',['typeList' =>$typeList,'patternList' =>$patternList,'ratioList' =>$ratioList,'optionList' =>$optionList]);
           
         }else return redirect()->route('quiz_template.index')->withStatus(__('No Access'));
    }

    public function makeSample(Request $request)
    {
       
          $request->validate([
            'name' => ['required'],
            'template_name' => ['required'],
            'option_type_id' => ['required'],
            'pattern' => ['required'],
            'topic_id' => ['required'],
            'ratio' => ['required'],
            
        ]);
        

     
        
        // Excel file name for download 
        $fileName = "sample" . date('Y-m-d') . ".xls"; 
        
        // Column names 
        $fields = array('Template Name','Template Type', 'Ratio','Option Type','Topic', 'Pattern','Question Background Color','Answer Background Color','Question Font Family','Question Font Color','Question Text Background Color','Question Text Border Color','Option Font Family','Option Font Color','Option Text Background Color','Answer Font Family','Answer Font Color','Answer Text Background Color','Answer Text Border Color');
         /*make image*/
        // if ($request->hasFile('image')) {
           
        //     $extension = $request->file('image')->getClientOriginalExtension();
        //     // Filename To store
        //     $fileNameToStore = time().'.'.$extension;

        //     $request->image->move(public_path('uploads/template_image'), $fileNameToStore);
        //     $image = 'uploads/template_image/'.$fileNameToStore;
               
        // }
        $values = array($request->template_name,$request->name, $request->ratio,$request->option_type_id,implode(",",$request->topic_id),$request->pattern,$request->backgroundcolor,$request->answerbackgroundcolor,$request->question_fonts,$request->question_color,$request->question_textbgcolor,$request->question_bordercolor,$request->optionfonts,$request->optioncolor,$request->optiontextbgcolor,$request->option_fonts,$request->option_color,$request->option_textbgcolor,$request->option_bordercolor);   
      
        $delimiter = ","; 
        $filename = "sample_" . date('Y-m-d') . ".csv"; 
         
        // Create a file pointer 
        $f = fopen('php://memory', 'w'); 
         
        // Set column headers 
       
        fputcsv($f, $fields, $delimiter); 
        fputcsv($f, $values, $delimiter); 
        // Move back to beginning of file 
        fseek($f, 0); 
        
        // Set headers to download file rather than displayed 
        header('Content-Type: text/csv'); 
        header('Content-Disposition: attachment; filename="' . $filename . '";'); 
        
        //output all remaining data on a file pointer 
        fpassthru($f); 
          exit;
    }
    public function bulk_upload(){
        return view('quiz_template.bulk_upload');
    }
    
    public function bulkstore(Request $request)
    {
       
        $request->validate([
            'bulkupload' => ['required'],
        ]);


        if ($request->hasFile('bulkupload')) 
        {
        
            $extension = $request->file('bulkupload')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->bulkupload->move(public_path('uploads/quiz_template/bulk_upload'), $fileNameToStore);
            $path = public_path().'/uploads/quiz_template/bulk_upload/'.$fileNameToStore;



            $csv_data = array_map('str_getcsv', file( $path));
            array_shift($csv_data);
            //dd($csv_data);
            foreach($csv_data as $key=>$line){
                $template = new QuizTemplate;
                 //dd($line);
                // echo $num = count($line);die;
                $template->template_name = $line[0];
                $template->name = $line[1];
                $template->ratio = $line[2];
                $template->option_type_id = $line[3]; 
                //$template->image = $line[4];
                $template->topic_id = $line[4];
                $template->pattern = $line[5];
               
                $template->backgroundcolor = $line[6];
                $template->answerbackgroundcolor = $line[7];
                
                $template->question_fonts =$line[8];
                $template->question_fontcolor = $line[9];
                $template->question_fontbackgroundcolor = $line[10];
                
                $template->option_font = $line[12];
                $template->option_color = $line[13];
                $template->option_textbgcolor = $line[14];


                $pattern = QuizPattern::findorfail($line[5]);
                if($pattern!=null){
                    $html_string = $pattern->pattern_html_string;
                  
                    $fonts_array= explode(':',$line[8]);
                    $fontstyle= explode(' ',$fonts_array[1]);
                    
                    $style ='font-family:'.$fonts_array[0];
                    $style .=';font-weight:'.$fontstyle[0];
                    if(count($fontstyle)>1){
                        $style .=';font-style:'.$fontstyle[1];
                    }
            
                    if (strpos($html_string,'{font_family}') !== false) {
                        $html_string= str_replace('{font_family}',$style,$html_string);
                    }
                    
                    if (strpos($html_string,'{backgroundcolor}') !== false) {
                        $html_string= str_replace('{backgroundcolor}',$line[6],$html_string);
                    }
                    
                    if (strpos($html_string,'{question_fontcolor}') !== false) {
                        $html_string= str_replace('{question_fontcolor}',$line[9],$html_string);
                    }
                    
                    if (strpos($html_string,'{question_fontbackgroundcolor}') !== false) {
                        $html_string= str_replace('{question_fontbackgroundcolor}',$line[10],$html_string);
                    }
                    
                    if (strpos($html_string,'{question_border}') !== false) {
                        $html_string= str_replace('{question_border}',$line[11],$html_string);
                    }
                    
                    $optionfonts_array= explode(':',$line[12]);
                    $optionfontstyle= explode(' ',$optionfonts_array[1]);
                    
                    $optionstyle ='font-family:'.$optionfonts_array[0];
                    $optionstyle .=';font-weight:'.$optionfontstyle[0];
                    if(count($optionfontstyle)>1){
                        $optionstyle .=';font-style:'.$optionfontstyle[1];
                    }
            
                    if (strpos($html_string,'{optionfontfamily}') !== false) {
                        $html_string= str_replace('{optionfontfamily}',$optionstyle,$html_string);
                    }
                    
                    if (strpos($html_string,'{optionfontcolor}') !== false) {
                        $html_string= str_replace('{optionfontcolor}',$line[13],$html_string);
                    }
                    
                    if (strpos($html_string,'{optionfontbackgroundcolor}') !== false) {
                        $html_string= str_replace('{optionfontbackgroundcolor}',$line[14],$html_string);
                    }
                    
                    
                    $answerfonts_array= explode(':',$line[15]);
                    $answerfontstyle= explode(' ',$answerfonts_array[1]);
                    
                    $answerstyle ='font-family:'.$answerfonts_array[0];
                    $answerstyle .=';font-weight:'.$answerfontstyle[0];
                    if(count($answerfontstyle)>1){
                        $answerstyle .=';font-style:'.$answerfontstyle[1];
                    }
            
                    if (strpos($html_string,'{option_font_family}') !== false) {
                        $html_string= str_replace('{option_font_family}',$optionstyle,$html_string);
                    }
                    
                    if (strpos($html_string,'{option_fontcolor}') !== false) {
                        $html_string= str_replace('{option_fontcolor}',$line[16],$html_string);
                    }
                    
                    if (strpos($html_string,'{option_fontbackgroundcolor}') !== false) {
                        $html_string= str_replace('{option_fontbackgroundcolor}',$line[17],$html_string);
                    }
                     
                    if (strpos($html_string,'{answer_border}') !== false) {
                        $html_string= str_replace('{answer_border}',$line[18],$html_string);
                    }
                    
                }
                
                // $html_string_template= $html_string;
                // $html = '<table style="width: 100%;margin:0 auto;background-color: #fff;margin-bottom:100px;">';
                // $html .=    '<tbody>';
                // $html .=        '<tr>';
                // $html .=            '<td class="step" style="text-align:left;width:50%;padding:50px;"><span style="font-weight:600;color:#000;font-size:40px;">{question_number}</span></td>';
                // $html .=        '</tr>';
                // $html .=    '</tbody>';
                //  $html .= '</table>';
                // if (strpos($html_string_template,'{header}') !== false) {
                //     $html_string_template= str_replace('{header}',$html,$html_string_template);
                // }    
                
                
                // $template->template_html = preg_replace('~>\s+<~', '><', $html_string_template);
                
                // $template->template_html_string =preg_replace('~>\s+<~', '><', $html_string_template);
                
                // if (strpos($html_string,'{header}') !== false) {
                //     $html_string= str_replace('{header}','',$html_string);
                // }    
                
                $template->template_html = preg_replace('~>\s+<~', '><', $html_string);
                
                $template->template_html_string =preg_replace('~>\s+<~', '><', $html_string);
                
                if (strpos($html_string,'{question_number}') !== false) {
                    $html_string= str_replace('{question_number}','',$html_string);
                } 
                
                if (strpos($html_string,'{question}') !== false) {
                  
                    $html_string= str_replace('{question}','A school boy who cuts classes frequently is a',$html_string);
                }
                if (strpos($html_string,'{option1}') !== false) {
                 
                    $html_string= str_replace('{option1}','Truant',$html_string);
                }
                if (strpos($html_string,'{option2}') !== false) {
                 
                    $html_string= str_replace('{option2}','Martinet',$html_string);
                }
                 if (strpos($html_string,'{option3}') !== false) {
                 
                    $html_string= str_replace('{option3}','Defeatist',$html_string);
                }
                 if (strpos($html_string,'{option4}') !== false) {
                 
                    $html_string= str_replace('{option4}','Sycophant',$html_string);
                }
                
                $html_string = preg_replace('~>\s+<~', '><', $html_string);
                
                $htmlpath = "uploads/quiz_temp_html/".Auth::user()->id;
                $html_url = $htmlpath."/".time().'.html';
                $html_path=public_path()."/".$html_url;
                $destinationHtmlPath = public_path($htmlpath);   
                if( !is_dir( $destinationHtmlPath ) ) mkdir( $destinationHtmlPath, 0755, true );
                
                file_put_contents($html_path, $html_string);
                
                
                $htmlFile = $html_path;
                    
                $imagepath = "uploads/quiz_template_image/".Auth::user()->id;
                $image_url = $imagepath."/".$key.'_'.time().'.jpg';
                $image_path=public_path()."/".$image_url;
                $destinationPath = public_path($imagepath);   
                if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                
                
                $outputImage = $image_path;
                
                // $command = "php artisan convert:html-to-image $htmlFile $outputImage";
                
                $result = QuizRatio::where('id','=',$line[2])->first();
             
                if(!empty($result) && $result->value=="16:9"){
                    $command = "php artisan convert:html-to-image-template $htmlFile $outputImage";
                }
                if(!empty($result) && $result->value=="9:16"){
                     $command = "php artisan convert:html-to-image-vertical-template $htmlFile $outputImage";
                }
                
                $output = shell_exec($command);
        
        
                
               /* $options = new Options();
                $options->setIsRemoteEnabled(true);
                $dompdf = new Dompdf($options);
                $dompdf->loadHtml( $html_string); 
                
                $ratio= QuizRatio::where("id",'=',$line[2])->first();
                if($ratio!=null){
                    if(!empty($ratio->value) && $ratio->value=="16:9"){
                        $customPaper = array(0,0,1440,1024);
                    }
                    if(!empty($ratio->value) && $ratio->value=="9:16"){
                          $customPaper = array(0,0,600,800);
                    }
                }
               
                //$customPaper = array(0,0,1440,1024); 
                // (Optional) Setup the paper size and orientation
               // $customPaper = array(0,0,612,612);
                // $customPaper = array(0,0,2048,1152);
                // $customPaper = array(0,0,2048,1152);
                $dompdf->setPaper($customPaper);
                $dompdf->curlAllowUnsafeSslRequests = true;
                
        
                
                // Render the HTML as PDF
                $dompdf->render();
                
                $pdfpath = "uploads/quiz_temp_pdf/".Auth::user()->id;
                $pdf_url = $pdfpath."/template_".$key."_".time().'.pdf';
                $pdf_path=public_path()."/".$pdf_url;
                 $destinationpdfPath = public_path($pdfpath);   
                if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                
                file_put_contents($pdf_path, $dompdf->output());
                
                $imagick = new \Imagick();
                $imagick->readImage($pdf_path);
                $imagepath = "uploads/quiz_template_image/".Auth::user()->id;
                $image_url = $imagepath."/template_".$key."_".time().'.jpg';
                $image_path=public_path()."/".$image_url;
                $destinationPath = public_path($imagepath);   
                if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                
                $imagick->writeImages($image_path, true);*/
                
                if(file_exists($image_path)) { 
                    $template->template_image =env("APP_URL").'public/'.$image_url;
                     $template->image =$image_url;
                }
            
                $template->save();

            }
            return redirect()->route('quiz_template.index')->withStatus(__('Bulk Upload successfully.'));
        }
    
           
    }
}
