<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $table = "results";
    protected $primaryKey = "id";

    protected $fillable = ['exam', 'question_id', 'yes_ans', 'no_ans', 'result_json'];
}
