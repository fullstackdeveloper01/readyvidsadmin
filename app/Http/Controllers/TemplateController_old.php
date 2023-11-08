<?php

namespace App\Http\Controllers;

use App\Template;
use App\TemplateType;
use App\Pattern;
use App\Video;
use App\Section;
use App\VideoText;
use App\Categories;
use App\Ratio;
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
class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $templates  = Template::select('templates.*','templates_type.type as template_name')
        //                     ->join('templates_type','templates.name','=','templates_type.id')
        //                     ->orderBy('templates.id','desc')
        //                     ->paginate(10);
        $templates  = Template::select('templates.*')
                            ->orderBy('templates.id','desc')
                            ->paginate(10);
        return view('template.index',compact('templates') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->hasRole('admin')){
           $typeList =TemplateType:: where('status','=',1)->get();
            $patternList =Pattern:: where('status','=',1)->get();
            $ratioList =Ratio:: where('status','=',1)->get();
            $videoList =Section:: where('status','=',1)->get();
            return view('template.create',['typeList' =>$typeList,'patternList' =>$patternList,'ratioList' =>$ratioList,'videoList' =>$videoList]);
           
        }else return redirect()->route('template.index')->withStatus(__('No Access'));
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
            'type' => ['required'],
            'pattern' => ['required'],
            'backgroundcolor' => ['required'],
            'template_html' => ['required'],
            'bordercolor' => ['required'],
           'ratio' => ['required'],
           'video_type' => ['required'],
           'subcategory' => ['required'],
        ]);
        


        $template = new Template;
        //$template_html = preg_replace('~>\s+<~', '><', $request->template_html);
        $template->name = $request->name;
        $template->type = $request->type;
        $template->pattern = $request->pattern;
        $template->backgroundcolor = $request->backgroundcolor;
        // $template->text = $request->text;
        $template->fonts = $request->font_family;//$request->fonts;
        $template->bordercolor = $request->bordercolor;
       // $template->template_image = $request->template_image;  
        $template->template_image_size = $request->template_image_size;
        $template->template_name = $request->template_name;
        $template->template_html = preg_replace('~>\s+<~', '><', $request->template_html);
        $template->template_html_string = preg_replace('~>\s+<~', '><', $request->template_html_string);
        $template->ratio = $request->ratio;
        $template->video_type = $request->video_type;
        $template->subcategory = implode(',',$request->subcategory);
                      
        // /*make image*/
        // if ($request->hasFile('image')) {
           
        //     $extension = $request->file('image')->getClientOriginalExtension();
        //     // Filename To store
        //     $fileNameToStore = time().'.'.$extension;

        //     $request->image->move(public_path('uploads/template_image'), $fileNameToStore);
        //     $template->image = 'uploads/template_image/'.$fileNameToStore;
               
        // }
        $html_string =  preg_replace('~>\s+<~', '><', $request->template_html);
        
         
        if (strpos($html_string,"{openbody}") !== false) {
           $html_string= str_replace('{openbody}',"<body style='",$html_string);
        }
        
        if (strpos($html_string,"{closebody}") !== false) {
           $html_string= str_replace('{closebody}',"'>",$html_string);
        }
        
        if (strpos($html_string,"{endbody}") !== false) {
           $html_string= str_replace('{endbody}',"</body>",$html_string);
        }
        if (strpos($html_string,'{font_family2}') !== false) {
         
            $html_string= str_replace('{font_family2}',"'KRDEV010', sans-serif;",$html_string);
        }
        if (strpos($html_string,'{font_family1}') !== false) {
         
            $html_string= str_replace('{font_family1}',$request->font_family,$html_string);
        }
         echo $html_string;//die;                  
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isFontSubsettingEnabled', true); // Subsetting fonts can reduce file size
        $options->set('defaultFont', 'KRDEV010'); // Set a default font if necessary
 


        $options->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml("<html lang='hi'> 
<link href='https://fonts.googleapis.com/css2?family=Hind&family=Noto+Sans+Devanagari&family=Poppins&family=KRDEV010&display=swap' rel='stylesheet'><style>
        body {
            font-family: 'KRDEV010';
        }

        @font-face {
          font-style: normal;
          font-weight: normal;
          font-family: 'Hind', sans-serif;
            font-family: 'Noto Sans Devanagari', sans-serif;
            font-family: 'Poppins', sans-serif;
            font-family:'KRDEV010';
          src: url(https://admin.readyvids.com/vendor/vendor/dompdf/dompdf/lib/gfont/KRDEV010.TTF) format('truetype');
        }
    </style>".$html_string."</html>"); 
        $result = Ratio::where('id','=',$request->ratio)->first();
        if(!empty($result) && $result->value=="16:9"){
            $customPaper = array(0,0,1440,1024);
        }
        if(!empty($result) && $result->value=="9:16"){
              $customPaper = array(0,0,600,800);
        }
        // (Optional) Setup the paper size and orientation
      // $customPaper = array(0,0,612,612);
        // $customPaper = array(0,0,2048,1152);
        // $customPaper = array(0,0,2048,1152);
        $dompdf->setPaper($customPaper);
        $dompdf->curlAllowUnsafeSslRequests = true;
        

        
        // Render the HTML as PDF
        $dompdf->render();
        
        $pdfpath = "uploads/temp_pdf/".Auth::user()->id;
      echo  $pdf_url = $pdfpath."/".time().'.pdf';
        $pdf_path=public_path()."/".$pdf_url;
       
        $destinationpdfPath = public_path($pdfpath);   
        if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
        
        file_put_contents($pdf_path, $dompdf->output());
        
        $imagick = new \Imagick();
        $imagick->readImage($pdf_path);
        $imagepath = "uploads/template_image/".Auth::user()->id;
        $image_url = $imagepath."/".time().'.jpg';
         $image_path=public_path()."/".$image_url;
        $destinationPath = public_path($imagepath);   
        if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
        
        $imagick->writeImages($image_path, true);
        
        if(file_exists($image_path)) { 
            $template->image =$image_url;
             $template->template_image =env("APP_URL").'public/'.$image_url;
        }
        dd($template);
       
        $template->save();
      
        return redirect()->route('template.create')->withStatus(__('Template successfully created.'));
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
       
        return view('template.show',compact('video_url'));
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
       
        return view('template.edit', compact('template','typeList','patternList','ratioList'));
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
        return redirect()->route('template.index')->withStatus(__('Template successfully updated.'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Template $template)
    {
        $affectedRows =  $template->delete();
        return redirect()->route('template.index')->withStatus(__('Template successfully deleted.'));
      
        
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
            $typeList =TemplateType:: where('status','=',1)->get();
            $patternList =Pattern:: where('status','=',1)->get();
            $ratioList =Ratio:: where('status','=',1)->get();
            $videoList =Section:: where('status','=',1)->get();
             return view('template.makesampledownload',['typeList' =>$typeList,'patternList' =>$patternList,'ratioList' =>$ratioList,'videoList' =>$videoList]);
         }else return redirect()->route('template.index')->withStatus(__('No Access'));
    }

    public function makeSample(Request $request)
    {
        //dd($request);
          $request->validate([
            'name' => ['required'],
            'template_name' => ['required'],
            'type' => ['required'],
            'pattern' => ['required'],
            'video_type' => ['required'],
            'ratio' => ['required'],
            'subcategory' => ['required'],
            
        ]);
        

     
        
        // Excel file name for download 
        $fileName = "sample" . date('Y-m-d') . ".xls"; 
        
        // Column names 
        $fields = array('Template Type', 'Ratio','Image Type','Video Type', 'Subcategory', 'Pattern','Font Family','Template Name','Background Color','Border Color');
         /*make image*/
        if ($request->hasFile('image')) {
           
            $extension = $request->file('image')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->image->move(public_path('uploads/template_image'), $fileNameToStore);
            $image = 'uploads/template_image/'.$fileNameToStore;
               
        }
        $values = array($request->name, $request->ratio,$request->type,$request->video_type,implode(',',$request->subcategory),$request->pattern,$request->fonts,$request->template_name,$request->backgroundcolor,$request->bordercolor);     
        $templateType= TemplateType::findorfail($request->name);
        $type= $templateType->type;
        $type_array = explode(' ',$type);
       // dd($type_array);
        for($counter=1;$counter<=$type_array[0];$counter++){
            array_push($fields,"Line".$counter." Font Color");
            array_push($values,"#000000");
            array_push($fields,"Line".$counter." Font BackgroundColor");
            array_push($values,"#000000");
        }
      
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
        return view('template.bulk_upload');
    }
    
    public function bulkstore(Request $request)
    {
       //dd($request);
        $request->validate([
            'bulkupload' => ['required'],
        ]);


        if ($request->hasFile('bulkupload')) 
        {
        
            $extension = $request->file('bulkupload')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->bulkupload->move(public_path('uploads/video/bulk_upload'), $fileNameToStore);
            $path = public_path().'/uploads/video/bulk_upload/'.$fileNameToStore;



            $csv_data = array_map('str_getcsv', file( $path));
            array_shift($csv_data);
            //dd($csv_data);
            foreach($csv_data as $key=>$line){
                $template = new Template;
                 //dd($line);
                // echo $num = count($line);die;
               
                $template->name = $line[0];
                $template->ratio = $line[1];
                $template->type = $line[2];
                $template->video_type = $line[3];
                $template->subcategory = $line[4];
                $template->pattern = $line[5];
               // $template->image = $line[5];
                $template->template_name = $line[7];
                $pattern = Pattern::findorfail($line[5]);
                if($pattern!=null){
                    $html_string = $pattern->pattern_html_string;
                    $templatetype = TemplateType::findorfail($line[0]);
                    $linevalue= $templatetype->type;
                    $line_array= explode(' ',$linevalue);
                    $fonts_array= explode(':',$line[6]);
                    $fontstyle= explode(' ',$fonts_array[1]);
                    
                    $style ='font-family:'.$fonts_array[0];
                    $style .=';font-weight:'.$fontstyle[0];
                    if(count($fontstyle)>1){
                        $style .=';font-style:'.$fontstyle[1];
                    }
            
                    if (strpos($html_string,'{fontfamily}') !== false) {
                        $html_string= str_replace('{fontfamily}',$style,$html_string);
                    }
                    if (strpos($html_string,'{fontweight}') !== false) {
                        $html_string= str_replace('{fontweight}',';font-weight:'.$fontstyle[0],$html_string);
                    }
                    
                    if (strpos($html_string,'{backgroundcolor}') !== false) {
                        $html_string= str_replace('{backgroundcolor}',$line[8],$html_string);
                    }
                    
                    if (strpos($html_string,'{bordercolor}') !== false) {
                        $html_string= str_replace('{bordercolor}',$line[9],$html_string);
                    }
                    $no = 10; 
                    for($index=0;$index<$line_array[0];$index++){
                        $fontcolor = "{line".($index+1)."fontcolor}";
                        $fontbackgroundcolor = "{line".($index+1)."fontbackgroundcolor}";
                      
                        if (strpos($html_string,$fontcolor) !== false) {
                            $html_string= str_replace($fontcolor,$line[$no],$html_string);
                        }
                         $no=$no+1;
                        if (strpos($html_string,$fontbackgroundcolor) !== false) {
                           $html_string= str_replace($fontbackgroundcolor,$line[$no],$html_string);
                        } 
                        $no++;
                    }
                    
                }
     
                $template->template_html = $html_string;
                $template->template_html_string =$html_string;
                $template->template_image_size =$pattern->image_size;
                $template->fonts =$line[6];
                $template->backgroundcolor =$line[8];
                $template->bordercolor =$line[9];
               // echo $html_string;die;
               // dd($pattern);
               
                        
                if (strpos($html_string,'{img}') !== false) {
                    if($pattern->image_size!=null && $line[3]=='1'){
                        $path=env('APP_URL').'public/'.$pattern->image_size.'.png';//'https://img.freepik.com/free-vector/hand-painted-watercolor-pastel-sky-background_23-2148902771.jpg';
                    }else{
                        $path = 'https://img.freepik.com/free-vector/hand-painted-watercolor-pastel-sky-background_23-2148902771.jpg';
                    }
                    //$path= env("APP_URL").'public/uploads/video/thumbnail_image/1683275893.png';
                    $html_string= str_replace('{img}',$path,$html_string);
                } 
                
               
               if($line[3]=='5'){
                   if (strpos($html_string,'{text1}') !== false) {
                  
                        $html_string= str_replace('{text1}','Aback',$html_string);
                    }
                    
                    if (strpos($html_string,'{text2}') !== false) {
                     
                        $html_string= str_replace('{text2}','चौंका देनांद',$html_string);
                    }
                    if (strpos($html_string,'{text3}') !== false) {
                      
                        $html_string= str_replace('{text3}','He was taken aback by his response.',$html_string);
                    }
                     if (strpos($html_string,'{text4}') !== false) {
                      
                        $html_string= str_replace('{text4}','उसके जवाब से वह चकित रह गया।',$html_string);
                    }
               }
               elseif($line[3]=='4'){
                   if (strpos($html_string,'{text1}') !== false) {
                  
                        $html_string= str_replace('{text1}','Do you need an extra blanket?',$html_string);
                    }
                    
                    if (strpos($html_string,'{text2}') !== false) {
                     
                        $html_string= str_replace('{text2}','क्या आपको एक और कंबल की जरूरत है?',$html_string);
                    }
                    if (strpos($html_string,'{text3}') !== false) {
                      
                        $html_string= str_replace('{text3}','Do you need an extra blanket?',$html_string);
                    }
                     if (strpos($html_string,'{text4}') !== false) {
                      
                        $html_string= str_replace('{text4}','क्या आपको एक और कंबल की जरूरत है?',$html_string);
                    }
               }
               elseif($line[3]=='6'){
                   if (strpos($html_string,'{text1}') !== false) {
                  
                        $html_string= str_replace('{text1}','Do you need an extra blanket?',$html_string);
                    }
                    
                    if (strpos($html_string,'{text2}') !== false) {
                     
                        $html_string= str_replace('{text2}','No, that would be enough for me.',$html_string);
                    }
                    if (strpos($html_string,'{text3}') !== false) {
                      
                        $html_string= str_replace('{text3}','क्या आपको एक और कंबल की जरूरत है?',$html_string);
                    }
                     if (strpos($html_string,'{text4}') !== false) {
                      
                        $html_string= str_replace('{text4}','नहीं, मेरे लिए यह ही काफी होगा।',$html_string);
                    }
               }
               elseif($line[3]=='3'){
                   if (strpos($html_string,'{text1}') !== false) {
                  
                        $html_string= str_replace('{text1}','Rolling pin',$html_string);
                    }
                    
                    if (strpos($html_string,'{text2}') !== false) {
                     
                        $html_string= str_replace('{text2}','बेलन',$html_string);
                    }
                    if (strpos($html_string,'{text3}') !== false) {
                      
                        $html_string= str_replace('{text3}','Rolling pin',$html_string);
                    }
                     if (strpos($html_string,'{text4}') !== false) {
                      
                        $html_string= str_replace('{text4}','बेलन।',$html_string);
                    }
               }
               else{
                   if (strpos($html_string,'{text1}') !== false) {
                  
                        $html_string= str_replace('{text1}','Can you come to pick me?',$html_string);
                    }
                    
                    if (strpos($html_string,'{text2}') !== false) {
                     
                        $html_string= str_replace('{text2}','क्या आप मुझे लेने आ सकते हैं?',$html_string);
                    }
                    if (strpos($html_string,'{text3}') !== false) {
                      
                        $html_string= str_replace('{text3}','Can you come to pick me?',$html_string);
                    }
                     if (strpos($html_string,'{text4}') !== false) {
                      
                        $html_string= str_replace('{text4}','Can you come to pick me?',$html_string);
                    }
               }
              
                
                if (strpos($html_string,'{font_family2}') !== false) {
                 
                    $html_string= str_replace('{font_family2}',"'Hind', serif;",$html_string);
                }
                
                if (strpos($html_string,'{font_family1}') !== false) {
                 
                    $html_string= str_replace('{font_family1}',$fonts_array[0],$html_string);
                }
               
                $options = new Options();
                $options->setIsRemoteEnabled(true);
                $dompdf = new Dompdf($options);
                $dompdf->loadHtml( $html_string); 
                
                $result = Ratio::where('id','=',$line[1])->first();
                if(!empty($result) && $result->value=="16:9"){
                    $customPaper = array(0,0,1440,1024);
                }
                if(!empty($result) && $result->value=="9:16"){
                       $customPaper = array(0,0,600,800);
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
                
                $pdfpath = "uploads/temp_pdf/".Auth::user()->id;
                $pdf_url = $pdfpath."/template_".$key."_".time().'.pdf';
                $pdf_path=public_path()."/".$pdf_url;
                $destinationpdfPath = public_path($pdfpath);   
                if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                
                file_put_contents($pdf_path, $dompdf->output());
                
                $imagick = new \Imagick();
                $imagick->readImage($pdf_path);
                $imagepath = "uploads/template_image/".Auth::user()->id;
                $image_url = $imagepath."/template_".$key."_".time().'.jpg';
                $image_path=public_path()."/".$image_url;
                $destinationPath = public_path($imagepath);   
                if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                
                $imagick->writeImages($image_path, true);
                
                if(file_exists($image_path)) { 
                    $template->template_image =env("APP_URL").'public/'.$image_url;
                    $template->image =$image_url;
                }
                //dd($template);
                $template->save();

            }
            return redirect()->route('template.index')->withStatus(__('Bulk Upload successfully.'));
        }
    
           
    }
}
