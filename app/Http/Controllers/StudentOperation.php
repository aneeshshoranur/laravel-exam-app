<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Result;
use App\Models\User;
use App\Models\UserExam;

class StudentOperation extends Controller
{
    /**
     * Method to Show student dashboard
     */
    public function dashboard()
    {
        $data['portal_exams'] = Exam::select(['exams.*', 'categories.name as cat_name'])
            ->join('categories', 'exams.category', '=', 'categories.id')
            ->orderBy('id', 'desc')->where('exams.status', '1')->get()->toArray();
        return view('student.dashboard', $data);
    }

    /**
     * Method to show Exam Page.
     * */
    public function exam()
    {
        $studentInfo = UserExam::select(['user_exams.*', 'users.name', 'exams.title', 'exams.exam_date'])
            ->join('users', 'users.id', '=', 'user_exams.user_id')
            ->join('exams', 'user_exams.exam_id', '=', 'exams.id')->orderBy('user_exams.exam_id', 'desc')
            ->where('user_exams.user_id', Session::get('id'))
            ->where('user_exams.std_status', '1')
            ->where('exams.status', '1')
            ->get()->toArray();

        return view('student.exam', ['student_info' => $studentInfo]);
    }

    /**
     * join exam page
     * */
    public function join_exam($id)
    {
        $question = Question::where('exam_id', $id)->get();
        $exam = Exam::where('id', $id)->get()->first();
        return view('student.join_exam', ['question' => $question, 'exam' => $exam]);
    }

    /**
     * Method to submit Questions
     */
    public function submit_questions(Request $request)
    {
        $yesAns = 0;
        $noAns = 0;
        $data = $request->all();
        $result = array();
        for ($i = 1; $i <= $request->index; $i++) {
            if (isset($data['question' . $i])) {
                $q = Question::where('id', $data['question' . $i])->get()->first();

                if ($q->ans == $data['ans' . $i]) {
                    $result[$data['question' . $i]] = 'YES';
                    $yesAns++;
                } else {
                    $result[$data['question' . $i]] = 'NO';
                    $noAns++;
                }
            }
        }

        $studentInfo = UserExam::where('user_id', Session::get('id'))->where('exam_id', $request->exam_id)->get()->first();
        $studentInfo->exam_joined = 1;
        $studentInfo->update();

        $res = new Result();
        $res->exam_id = $request->exam_id;
        $res->user_id = Session::get('id');
        $res->yes_ans = $yesAns;
        $res->no_ans = $noAns;
        $res->result_json = json_encode($result);

        echo $res->save();
        return redirect(url('student/exam'));
    }

    /**
     * Method to apply the exam.
     */
    public function apply_exam($id)
    {

        $checkuser = UserExam::where('user_id', Session::get('id'))->where('exam_id', $id)->get()->first();

        if ($checkuser) {
            $arr = array('status' => 'false', 'message' => 'Already applied, see your exam section');
        } else {
            $examUser = new UserExam();
            $examUser->user_id = Session::get('id');
            $examUser->exam_id = $id;
            $examUser->std_status = 1;
            $examUser->exam_joined = 0;

            $examUser->save();

            $arr = array('status' => 'true', 'message' => 'applied successfully', 'reload' => url('student/dashboard'));
        }

        echo json_encode($arr);
    }

    /**
     * Method to show results to student
     */
    public function view_result($id)
    {

        $data['result_info'] = Result::where('exam_id', $id)->where('user_id', Session::get('id'))->get()->first();

        $data['student_info'] = User::where('id', Session::get('id'))->get()->first();
        $data['exam_info'] = Exam::where('id', $id)->get()->first();
        return view('student.view_result', $data);
    }

    /**
     * Method to show answers
     */
    public function view_answer($id)
    {
        $data['question'] = Question::where('exam_id', $id)->get()->toArray();
        return view('student.view_amswer', $data);
    }
}
