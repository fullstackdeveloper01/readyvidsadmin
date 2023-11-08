<?php

namespace App\Exports;

use App\QuestionAnswerVideoVoice;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class QuestionAnswerVideoVoiceExport implements FromCollection,WithHeadings {
    public function headings(): array
    {
        return [
            "name",
            
            "Voice1 (Question)",
            "Voice2 (Question)",
            "Voice3 (Question)",
            "Voice4 (Question)",
            "Voice5 (Question)",
            "Voice6 (Question)",
            "Voice7 (Question)",
            "Voice8 (Question)",
            "Voice9 (Question)",
            "Voice10 (Question)",
            "Voice11 (Question)",
            "Voice12 (Question)",
            "Voice13 (Question)",
            "Voice14 (Question)",
            "Voice15 (Question)",
            "Voice16 (Question)",
            "Voice17 (Question)",
            "Voice18 (Question)",
            "Voice19 (Question)",
            "Voice20 (Question)",
            "Voice1 (Answer)",
            "Voice2 (Answer)",
            "Voice3 (Answer)",
            "Voice4 (Answer)",
            "Voice5 (Answer)",
            "Voice6 (Answer)",
            "Voice7 (Answer)",
            "Voice8 (Answer)",
            "Voice9 (Answer)",
            "Voice10 (Answer)",
            "Voice11 (Answer)",
            "Voice12 (Answer)",
            "Voice13 (Answer)",
            "Voice14 (Answer)",
            "Voice15 (Answer)",
            "Voice16 (Answer)",
            "Voice17 (Answer)",
            "Voice18 (Answer)",
            "Voice19 (Answer)",
            "Voice20 (Answer)"
            
            
        ];
    }
    public function collection()
    {
        $voices= QuestionAnswerVideoVoice::select('name','audio1','audio2','audio3','audio4','audio5','audio6','audio7','audio8','audio9','audio10','audio11','audio12','audio13','audio14','audio15','audio16','audio17','audio18','audio19','audio20','answer_audio1','answer_audio2','answer_audio3','answer_audio4','answer_audio5','answer_audio6','answer_audio7','answer_audio8','answer_audio9','answer_audio10','answer_audio11','answer_audio12','answer_audio13','answer_audio14','answer_audio15','answer_audio16','answer_audio17','answer_audio18','answer_audio19','answer_audio20');
        if(!empty($_GET['folder_id'])){
            $voices=$voices->where('folder_id','=',$_GET['folder_id']);
        }
        return $voices=  $voices->get();
    }
}