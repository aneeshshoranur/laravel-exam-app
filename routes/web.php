<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentOperation;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Default route - Application Landing Page
 * */
Route::get('/', function () {
    return view('welcome');
});


/**
 * Application home page
 */
Route::get('/home', function () {
    return view('home');
})->middleware(['auth'])->name('home');


/**
 * Routes for Teacher Authenticated
 */
Route::prefix('teacher')->middleware('theme:dashboard')->name('teacher.')->group(function(){       
        Route::middleware(['auth:teacher'])->group(function(){
            Route::get('/dashboard',[TeacherController::class,'index']);

            Route::get('/exam_category',[TeacherController::class,'exam_category']);
            Route::get('/delete_category/{id}',[TeacherController::class,'delete_category']);
            Route::get('/edit_category/{id}',[TeacherController::class,'edit_category']);
            Route::get('/category_status/{id}',[TeacherController::class,'category_status']);

            Route::get('/manage_exam',[TeacherController::class,'manage_exam']);
            Route::get('/exam_status/{id}',[TeacherController::class,'exam_status']);
            Route::get('/delete_exam/{id}',[TeacherController::class,'delete_exam']);
            Route::get('/edit_exam/{id}',[TeacherController::class,'edit_exam']);

            Route::get('/add_questions/{id}',[TeacherController::class,'add_questions']);
            Route::get('/question_status/{id}',[TeacherController::class,'question_status']);
            Route::get('/delete_question/{id}',[TeacherController::class,'delete_question']);
            Route::get('/update_question/{id}',[TeacherController::class,'update_question']);

            Route::get('/manage_students',[TeacherController::class,'manage_students']);
            Route::get('/student_status/{id}',[TeacherController::class,'student_status']);
            Route::get('/delete_students/{id}',[TeacherController::class,'delete_students']);

            
            Route::get('/registered_students',[TeacherController::class,'registered_students']);
            Route::get('/delete_registered_students/{id}',[TeacherController::class,'delete_registered_students']);

            Route::get('/apply_exam/{id}',[TeacherController::class,'apply_exam']);
            Route::get('/teacher_view_result/{id}',[TeacherController::class,'teacher_view_result']);
    
            Route::post('/edit_question_inner',[TeacherController::class,'edit_question_inner']);
            Route::post('/add_new_question',[TeacherController::class,'add_new_question']);
            Route::post('/edit_students_final',[TeacherController::class,'edit_students_final']);
            Route::post('/add_new_exam',[TeacherController::class,'add_new_exam']);
            Route::post('/add_new_category',[TeacherController::class,'add_new_category']);
            Route::post('/edit_new_category',[TeacherController::class,'edit_new_category']);
            Route::post('/edit_exam_sub',[TeacherController::class,'edit_exam_sub']);
            Route::post('/add_new_students',[TeacherController::class,'add_new_students']);        
        });
    });



/* Student section routes */
Route::prefix('student')->middleware('theme:dashboard')->name('student.')->group(function(){    

    Route::middleware(['auth:web'])->group(function(){    
        Route::get('/dashboard',[StudentOperation::class,'dashboard']);

        Route::get('/exam',[StudentOperation::class,'exam']);
        Route::get('/join_exam/{id}',[StudentOperation::class,'join_exam']);
        Route::get('/apply_exam/{id}',[StudentOperation::class,'apply_exam']);
        
        Route::post('/submit_questions',[StudentOperation::class,'submit_questions']);
        Route::get('/show_result/{id}',[StudentOperation::class,'show_result']);
        
        Route::get('/view_result/{id}',[StudentOperation::class,'view_result']);
        Route::get('/view_answer/{id}',[StudentOperation::class,'view_answer']);
        Route::get('/logout',[AuthenticatedSessionController::class,'destroy']);
    });    
});



require __DIR__.'/auth.php';
require __DIR__.'/teacher.php';