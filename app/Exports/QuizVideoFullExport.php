<?php

namespace App\Exports;

use App\QuizVideo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
class QuizVideoFullExport implements FromCollection,WithHeadings {
    
    public function headings(): array
    {
        return [
            "Id",
            "Country",
            "Subject",
            "Topic",
            "Question Speaker1",
            "Question Speaker2",
            "Question Speaker3",
            "Question Speaker4",
            "Question Speaker5",
            "Question Speaker6",
            "Question Speaker7",
            "Question Speaker8",
            "Question Speaker9",
            "Question Speaker10",
            "Question Speaker11",
            "Question Speaker12",
            "Question Speaker13",
            "Question Speaker14",
            "Question Speaker15",
            "Question Speaker16",
            "Question Speaker17",
            "Question Speaker18",
            "Question Speaker19",
            "Question Speaker20",
            
            "Answer Speaker1",
            "Answer Speaker2",
            "Answer Speaker3",
            "Answer Speaker4",
            "Answer Speaker5",
            "Answer Speaker6",
            "Answer Speaker7",
            "Answer Speaker8",
            "Answer Speaker9",
            "Answer Speaker10",
            "Answer Speaker11",
            "Answer Speaker12",
            "Answer Speaker13",
            "Answer Speaker14",
            "Answer Speaker15",
            "Answer Speaker16",
            "Answer Speaker17",
            "Answer Speaker18",
            "Answer Speaker19",
            "Answer Speaker20",
            "Question",
            "Option1",
            "Option2",
            "Option3",
            "Option4",
            "Option5",
            "Answer"
           
            
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
         $videos=QuizVideo::select('quiz_video.id','country.country_name','subjects.name as subject_name','topics.name as topic_name','quiz_video.audio1','quiz_video.audio2','quiz_video.audio3','quiz_video.audio4','quiz_video.audio5','quiz_video.audio6','quiz_video.audio7','quiz_video.audio8','quiz_video.audio9','quiz_video.audio10','quiz_video.audio11','quiz_video.audio12','quiz_video.audio13','quiz_video.audio14','quiz_video.audio15','quiz_video.audio16','quiz_video.audio17','quiz_video.audio18','quiz_video.audio19','quiz_video.audio20','quiz_video.answer_audio1','quiz_video.answer_audio2','quiz_video.answer_audio3','quiz_video.answer_audio4','quiz_video.answer_audio5','quiz_video.answer_audio6','quiz_video.answer_audio7','quiz_video.answer_audio8','quiz_video.answer_audio9','quiz_video.answer_audio10','quiz_video.answer_audio11','quiz_video.answer_audio12','quiz_video.answer_audio13','quiz_video.answer_audio14','quiz_video.answer_audio15','quiz_video.answer_audio16','quiz_video.answer_audio17','quiz_video.answer_audio18','quiz_video.answer_audio19','quiz_video.answer_audio20','question','option1','option2','option3','option4','option5','answer')
                        ->join('country','quiz_video.country_id','=','country.id')
                        ->join('subjects','quiz_video.subject_id','=','subjects.id')
                        ->join('topics','quiz_video.topic_id','=','topics.id');
                        
        if(!empty($_GET['country_id'])){
            $videos=$videos->where('quiz_video.country_id','=',$_GET['country_id']);
        }
        if(!empty($_GET['subject_id'])){
            $videos=$videos->where('quiz_video.subject_id','=',$_GET['subject_id']);
        }
        if(!empty($_GET['topic_id'])){
            $videos=$videos->where('quiz_video.topic_id','=',$_GET['topic_id']);
        }
        $videos=$videos->orderBy('id','desc')->get();
        return $videos;
    }
}
