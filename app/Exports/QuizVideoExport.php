<?php

namespace App\Exports;

use App\QuizVideo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QuizVideoExport implements FromCollection,WithHeadings {
    
    
    private $data,$data1;

    public function __construct($data,$data1)
    {
        $this->data = $data;
        $this->data1 = $data1;
    }
    
    public function headings(): array
    {
        return $this->data;
        /*
        return [
            "Template Type",
           "Country",
           "Subject",
           "Option Type",
           'Topic',
           'Question Speaker Audio 1',
           'Question Speaker Audio 2',
           'Question Speaker Audio 3',
           'Question Speaker Audio 4',
           'Question Speaker Audio 5',
           'Question Speaker Audio 6',
           'Question Speaker Audio 7',
           'Question Speaker Audio 8',
           'Question Speaker Audio 9',
           'Question Speaker Audio 10',
           'Question Speaker Audio 11',
           'Question Speaker Audio 12',
           'Question Speaker Audio 13',
           'Question Speaker Audio 14',
           'Question Speaker Audio 15',
           'Answer Speaker Audio 1',
           'Answer Speaker Audio 2',
           'Answer Speaker Audio 3',
           'Answer Speaker Audio 4',
           'Answer Speaker Audio 5',
           'Answer Speaker Audio 6',
           'Answer Speaker Audio 7',
           'Answer Speaker Audio 8',
           'Answer Speaker Audio 9',
           'Answer Speaker Audio 10',
           'Answer Speaker Audio 11',
           'Answer Speaker Audio 12',
           'Answer Speaker Audio 13',
           'Answer Speaker Audio 14',
           'Answer Speaker Audio 15'
            
        ];
        */
    }
    public function collection()
    {
          return collect([$this->data1]);
    
        /*
       return collect([
            [
                $this->data['template_type_id'],
                $this->data['template_type_id'],
                $this->data['subject_id'], $this->data['option_type_id'],$this->data['topic_id'],'uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3'
              
            ],
           
        ]);
        */
      
    }
}
