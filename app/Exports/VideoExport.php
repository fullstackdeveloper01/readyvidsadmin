<?php

namespace App\Exports;

use App\Video;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VideoExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
   
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
            'Video Type',
            'Template Type',
            'Primary Language',
            'Secondary Language',
            'Category',
            'Subcategory',
            'Image',
            'Male1 (Short)',
            'Male2 (Short)',
            'Male3 (Short)',
            'Male4 (Short)',
            'Male5 (Short)',
            'Female1 (Short)',
            'Female2 (Short)',
            'Female3 (Short)',
            'Female4 (Short)',
            'Female5 (Short)',
            'Male1 (Long)',
            'Male2 (Long)',
            'Male3 (Long)',
            'Male4 (Long)',
            'Male5 (Long)',
            'Female1 (Long)',
            'Female2 (Long)',
            'Female3 (Long)',
            'Female4 (Long)',
            'Female5 (Long)'
            
        ];
        */
    }
    public function collection()
    {
        return collect([($this->data1)]);
       /*
       return collect([
            [
                $this->data['section'],
                $this->data['template_type'], 
                $this->data['primary_language'], 
                $this->data['secondary_language'], 
                $this->data['category'], 
                $this->data['subcategory'],
                'https://readyvids.manageprojects.in/public/uploads/video/image/1668507503.jpg',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3',
                'https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3'
              
            ],
           
        ]);
      */
    }
}
