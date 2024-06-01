<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = "questions";

    protected $primaryKey = "id";
    protected $fillbale = ['exam_id', 'questions', 'ans', 'options', 'status'];
}
