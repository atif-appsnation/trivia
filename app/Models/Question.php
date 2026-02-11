<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['title' , 'answer' , 'category_id'];
    public $timestamps = false;

    public function category(){
        $this->belongsTo(Category::class , "category_id");
    }

    public function answers(){
        return $this->hasMany(Answer::class , "question_id");
    }

}
