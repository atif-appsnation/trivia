<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable= ['text' , 'image' , 'question_id' , 'isCorrect'];
    public $timestamps = false;

    public function question(){
        $this->belongsTo(Question::class , "question_id");
    }
    
}
