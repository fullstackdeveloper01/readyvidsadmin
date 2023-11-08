<?php

namespace App\Exports;

use App\Images;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\FromCollection;
 
class ImagesExport implements FromCollection,WithHeadings {
    public function headings(): array
    {
        return [
            "name",
            //"relative_image_path",
            "path"
            
        ];
    }
    public function collection()
    {
        $images= Images::select('name','relative_image_path');
        if(!empty($_GET['folder_id'])){
            $images=$images->where('folder_id','=',$_GET['folder_id']);
        }
        return $images=  $images->get();
    }
}