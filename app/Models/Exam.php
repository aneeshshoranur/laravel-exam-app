<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $table = "exams";

    protected $primaryKey = "id";

    protected $fillable = ['title', 'category', 'exam_date', 'status', 'exam_duration'];
}
