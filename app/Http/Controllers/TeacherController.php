<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Exam;
use App\Models\Student;
use App\Models\Portal;
use App\Models\User;
use App\Models\Question;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\UserExam;
use App\Models\Teacher;
use App\Models\Result;

class TeacherController extends Controller
{
    /**
     * Method to show Teacher dashboard 
     */
    public function index()
    {

        $user_count = User::get()->count();
        $exam_count = Exam::get()->count();
        $teacher_count = Teacher::get()->count();
        return view('teacher.dashboard', ['student' => $user_count, 'exam' => $exam_count, 'teacher' => $teacher_count]);
    }


    /**
     * Method to Show Exam categories
     * 
     */
    public function exam_category()
    {
        $data['category'] = Category::get()->toArray();
        return view('teacher.exam_category', $data);
    }


    /**
     * Method to Add new exam categories
     */
    public function add_new_category(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            $arr = array('status' => 'false', 'message' => $validator->errors()->all());
        } else {

            $cat = new Category();
            $cat->name = $request->name;
            $cat->status = 1;
            $cat->save();
            $arr = array('status' => 'true', 'message' => 'Success', 'reload' => url('teacher/exam_category'));
        }
        echo json_encode($arr);
    }

    /**
     * Method to delete category
     */
    public function delete_category($id)
    {
        $cat = Category::where('id', $id)->get()->first();
        $cat->delete();
        return redirect(url('teacher/exam_category'));
    }


    /**
     * Method to edit category
     */
    public function edit_category($id)
    {
        $category = Category::where('id', $id)->get()->first();
        return view('teacher.edit_category', ['category' => $category]);
    }


    /**
     * Method to update category
     */
    public function edit_new_category(Request $request)
    {
        $cat = Category::where('id', $request->id)->get()->first();
        $cat->name = $request->name;
        $cat->update();
        echo json_encode(array('status' => 'true', 'message' => 'updated successfully', 'reload' => url('teacher/exam_category')));
    }


    /**
     * Method to change category status
     */
    public function category_status($id)
    {
        $cat = Category::where('id', $id)->get()->first();

        if ($cat->status == 1)
            $status = 0;
        else
            $status = 1;

        $cat1 = Category::where('id', $id)->get()->first();
        $cat1->status = $status;
        $cat1->update();
    }


    /**
     * Method to list exams   
     */
    public function manage_exam()
    {
        $data['category'] = Category::where('status', '1')->get()->toArray();
        $data['exams'] = Exam::select(['exams.*', 'categories.name as cat_name'])->join('categories', 'exams.category', '=', 'categories.id')->get()->toArray();
        return view('teacher.manage_exam', $data);
    }


    /**
     * Method to add new exam
     */
    public function add_new_exam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required', 'exam_date' => 'required', 'exam_category' => 'required',
            'exam_duration' => 'required', 'pass_mark' => 'required'
        ]);

        if ($validator->fails()) {
            $arr = array('status' => 'false', 'message' => $validator->errors()->all());
        } else {

            $exam = new Exam();
            $exam->title = $request->title;
            $exam->exam_date = $request->exam_date;
            $exam->exam_duration = $request->exam_duration;
            $exam->category = $request->exam_category;
            $exam->status = 1;
            $exam->pass_mark = $request->pass_mark;
            $exam->save();

            $arr = array('status' => 'true', 'message' => 'exam added successfully', 'reload' => url('teacher/manage_exam'));
        }

        echo json_encode($arr);
    }


    /**
     * Method to update exam status
     */
    public function exam_status($id)
    {

        $exam = Exam::where('id', $id)->get()->first();

        if ($exam->status == 1) {
            $status = 0;
            $message = 'Exam Succesfully archived.';
        } else {
            $status = 1;
            $message = 'Exam Succesfully activated.';
        }

        $exam1 = Exam::where('id', $id)->get()->first();
        $exam1->status = $status;
        $exam1->update();
        $arr = array('status' => 'true', 'message' => $message, 'reload' => url('teacher/manage_exam'));
        echo json_encode($arr);
    }

    /**
     * Method to delete exam
     */
    public function delete_exam($id)
    {
        $exam1 = Exam::where('id', $id)->get()->first();
        $exam1->delete();
        return redirect(url('teacher/manage_exam'));
    }


    /**
     * Method to edit exam details
     */
    public function edit_exam($id)
    {
        $data['category'] = Category::where('status', '1')->get()->toArray();
        $data['exam'] = Exam::where('id', $id)->get()->first();

        return view('teacher.edit_exam', $data);
    }


    /**
     * Method to update exam details
     */
    public function edit_exam_sub(Request $request)
    {

        $exam = Exam::where('id', $request->id)->get()->first();
        $exam->title = $request->title;
        $exam->exam_date = $request->exam_date;
        $exam->category = $request->exam_category;
        $exam->exam_duration = $request->exam_duration;
        $exam->pass_mark = $request->pass_mark;

        $exam->update();

        echo json_encode(array('status' => 'true', 'message' => 'Successfully updated', 'reload' => url('teacher/manage_exam')));
    }


    /**
     * Method to show students
     */
    public function manage_students()
    {

        $data['exams'] = Exam::where('status', '1')->get()->toArray();
        $data['students'] = UserExam::select(['user_exams.*', 'users.name', 'exams.title as ex_name', 'exams.exam_date'])
            ->join('users', 'users.id', '=', 'user_exams.user_id')
            ->join('exams', 'user_exams.exam_id', '=', 'exams.id')->orderBy('user_exams.exam_id', 'desc')
            ->get()->toArray();

        return view('teacher.manage_students', $data);
    }


    /**
     * Method to add new student
     */
    public function add_new_students(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'mobile_no' => 'required',
            'exam' => 'required',
            'password' => 'required'

        ]);

        if ($validator->fails()) {
            $arr = array('status' => 'false', 'message' => $validator->errors()->all());
        } else {
            $std = new User();
            $std->name = $request->name;
            $std->email = $request->email;
            $std->mobile_no = $request->mobile_no;
            $std->exam = $request->exam;
            $std->password = Hash::make($request->password);

            $std->status = 1;

            $std->save();

            $arr = array('status' => 'true', 'message' => 'student added successfully', 'reload' => url('teacher/manage_students'));
        }

        echo json_encode($arr);
    }


    /**
     * Method to update student status
     */
    public function student_status($id)
    {
        $std = UserExam::where('id', $id)->get()->first();

        if ($std->std_status == 1)
            $status = 0;
        else
            $status = 1;

        $std1 = UserExam::where('id', $id)->get()->first();
        $std1->std_status = $status;
        $std1->update();
    }


    /**
     * Method to delete student
     */
    public function delete_students($id)
    {

        $std = UserExam::where('id', $id)->get()->first();
        $std->delete();
        return redirect('teacher/manage_students');
    }


    /**
     * Method to edit student details
     */
    public function edit_students_final(Request $request)
    {
        $std = User::where('id', $request->id)->get()->first();
        $std->name = $request->name;
        $std->email = $request->email;
        $std->mobile_no = $request->mobile_no;
        $std->exam = $request->exam;
        if ($request->password != '')
            $std->password = $request->password;

        $std->update();
        echo json_encode(array('status' => 'true', 'message' => 'Successfully updated', 'reload' => url('teacher/manage_students')));
    }


    /**
     * Method to list registered student
     */
    public function registered_students()
    {

        $data['users'] = User::get()->all();
        return view('teacher.registered_students', $data);
    }


    /**
     * Method to delete a registered student
     */
    public function delete_registered_students($id)
    {
        $std = User::where('id', $id)->get()->first();
        $std->delete();
        return redirect('teacher/registered_students');
    }

    /**
     * Method to add questions to an exam
     */
    public function add_questions($id)
    {
        $data['questions'] = Question::where('exam_id', $id)->get()->toArray();
        return view('teacher.add_questions', $data);
    }


    /**
     * Method to save a new question
     */
    public function add_new_question(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'option_1' => 'required',
            'option_2' => 'required',
            'option_3' => 'required',
            'option_4' => 'required',
            'ans' => 'required'
        ]);

        if ($validator->fails()) {
            $arr = array('status' => 'flase', 'message' => $validator->errors()->all());
        } else {

            $q = new Question();
            $q->exam_id = $request->exam_id;
            $q->questions = $request->question;

            if ($request->ans == 'option_1') {
                $q->ans = $request->option_1;
            } elseif ($request->ans == 'option_2') {
                $q->ans = $request->option_2;
            } elseif ($request->ans == 'option_3') {
                $q->ans = $request->option_3;
            } else {
                $q->ans = $request->option_4;
            }

            $q->status = 1;
            $q->options = json_encode(array('option1' => $request->option_1, 'option2' => $request->option_2, 'option3' => $request->option_3, 'option4' => $request->option_4));
            $q->save();

            $arr = array('status' => 'true', 'message' => 'successfully added', 'reload' => url('teacher/add_questions/' . $request->exam_id));
        }

        echo json_encode($arr);
    }

    /**
     * Method to update question status
     */
    public function question_status($id)
    {
        $p = Question::where('id', $id)->get()->first();

        if ($p->status == 1)
            $status = 0;
        else
            $status = 1;

        $p1 = Question::where('id', $id)->get()->first();
        $p1->status = $status;
        $p1->update();
    }


    /**
     * Method to delete question
     */
    public function delete_question($id)
    {
        $q = Question::where('id', $id)->get()->first();
        $exam_id = $q->exam_id;
        $q->delete();

        return redirect(url('teacher/add_questions/' . $exam_id));
    }

    /**
     * Method to edit question
     */
    public function update_question($id)
    {

        $data['q'] = Question::where('id', $id)->get()->toArray();

        return view('teacher.update_question', $data);
    }


    /**
     * Method to update question details
     */
    public function edit_question_inner(Request $request)
    {

        $q = Question::where('id', $request->id)->get()->first();

        $q->questions = $request->question;

        if ($request->ans == 'option_1') {
            $q->ans = $request->option_1;
        } elseif ($request->ans == 'option_2') {
            $q->ans = $request->option_2;
        } elseif ($request->ans == 'option_3') {
            $q->ans = $request->option_3;
        } else {
            $q->ans = $request->option_4;
        }

        $q->options = json_encode(array('option1' => $request->option_1, 'option2' => $request->option_2, 'option3' => $request->option_3, 'option4' => $request->option_4));

        $q->update();

        echo json_encode(array('status' => 'true', 'message' => 'successfully updated', 'reload' => url('teacher/add_questions/' . $q->exam_id)));
    }


    /**
     * Method to show result of a student
     */
    public function teacher_view_result($id)
    {
        $std_exam = UserExam::where('id', $id)->get()->first();

        $data['student_info'] = User::where('id', $std_exam->user_id)->get()->first();

        $data['exam_info'] = Exam::where('id', $std_exam->exam_id)->get()->first();

        $data['result_info'] = Result::where('exam_id', $std_exam->exam_id)->where('user_id', $std_exam->user_id)->get()->first();

        return view('teacher.teacher_view_result', $data);
    }
}
