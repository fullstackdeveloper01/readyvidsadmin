<?php

namespace App\Exports;

use App\VideoVoice;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\FromCollection;
 
class VideoVoiceExport implements FromCollection,WithHeadings {
    public function headings(): array
    {
        return [
            "name",
            //"relative_image_path",
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
    public function collection()
    {
        $voices= VideoVoice::select('name','audio_m1','audio_m2','audio_m3','audio_m4','audio_m5','audio_f1','audio_f2','audio_f3','audio_f4','audio_f5','audio_m1_long','audio_m2_long','audio_m3_long','audio_m4_long','audio_m5_long','audio_f1_long','audio_f2_long','audio_f3_long','audio_f4_long','audio_f5_long');
        if(!empty($_GET['folder_id'])){
            $voices=$voices->where('folder_id','=',$_GET['folder_id']);
        }
        return $voices=  $voices->get();
    }
}