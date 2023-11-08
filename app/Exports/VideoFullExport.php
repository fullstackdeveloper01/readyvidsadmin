<?php

namespace App\Exports;

use App\Video;
use App\VideoText;
use App\VideoTextMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VideoFullExport implements FromCollection,WithHeadings {
    
    public function headings(): array
    {
        return [
            "Id",
            "Video Type",
            "Category Name",
            "Subcategory Name",
            "Image",
            "Male1 (Short)",
            "Male2 (Short)",
            "Male3 (Short)",
            "Male4 (Short)",
            "Male5 (Short)",
            "Female1 (Short)",
            "Female2 (Short)",
            "Female3 (Short)",
            "Female4 (Short)",
            "Female5 (Short)",
            "Male1 (Long)",
            "Male2 (Long)",
            "Male3 (Long)",
            "Male4 (Long)",
            "Male5 (Long)",
            "Female1 (Long)",
            "Female2 (Long)",
            "Female3 (Long)",
            "Female4 (Long)",
            "Female5 (Long)"
            
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
         $videos=Video::select('video.id','section.name as section_name','categories.name as category_name','sucategory.name as subcategory_name','video.image','video.audio_m','video.audio_m1','video.audio_m2','video.audio_m3','video.audio_m4','video.audio_f','video.audio_f1','video.audio_f2','video.audio_f3','video.audio_f4','video.audio_m1_long','video.audio_m2_long','video.audio_m3_long','video.audio_m4_long','video.audio_m5_long','video.audio_f1_long','video.audio_f2_long','video.audio_f3_long','video.audio_f4_long','video.audio_f5_long')
                        ->join('section','video.section','=','section.id')
                        ->join('categories','video.category','=','categories.id')
                        ->join('categories as sucategory','video.subcategory','=','sucategory.id')
                        ->orderBy('id','desc');
           
        if(!empty($_GET['subcategory_id'])){
            $videos=$videos->where('video.subcategory','=',$_GET['subcategory_id']);
        }
        if(!empty($_GET['category_id'])){
            $videos=$videos->where('video.category','=',$_GET['category_id']);
        }
        
        if(!empty($_GET['primary_language'])){
            $videos=$videos->where('video.primary_language','=',$_GET['primary_language']);
        }
        if(!empty($_GET['secondary_language'])){
            $videos=$videos->where('video.secondary_language','=',$_GET['secondary_language']);
        }
                        
        $videos= $videos->get();
       
        foreach($videos as $video){
           
            $videotexts= VideoTextMapping::join('video_text','video_text_mapping.text_id','=','video_text.id')->where('video_text_mapping.video_id','=',$video->id)->get();
            
             for($counter=0;$counter< count($videotexts);$counter++){
                //array_push($headings,"Text".$counter);
                $index=$counter+1;
                $video['text'.$index]=$videotexts[$counter]['text'];
            }
        }       
         
                                   
        return $videos;
    }
}
