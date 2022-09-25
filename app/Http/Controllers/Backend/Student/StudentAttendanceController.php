<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignStudent;
use App\Models\User;
use App\Models\DiscountStudent;

use App\Models\StudentYear;
use App\Models\StudentClass;
use App\Models\StudentGroup;
use App\Models\StudentShift;
use DB;
use PDF;

use App\Models\Designation;
use App\Models\LeavePurpose;

use App\Models\StudentAttendance;

class StudentAttendanceController extends Controller
{
    public function AttendanceView(){
        $data['allData'] = StudentAttendance::select('date')->groupBy('date')->orderBy('id','DESC')->get();
    	// $data['allData'] = EmployeeAttendance::orderBy('id','DESC')->get();
    	return view('backend.student.student_attendance.student_attendance_view',$data);
    }


    public function AttendanceAdd(){
    	$data['students'] = User::where('usertype','student')->get();
    	return view('backend.student.student_attendance.student_attendance_add',$data);

    }


    public function AttendanceStore(Request $request){

    	StudentAttendance::where('date', date('Y-m-d', strtotime($request->date)))->delete();
    	$countstudent = count($request->student_id);
    	for ($i=0; $i <$countstudent ; $i++) { 
    		$attend_status = 'attend_status'.$i;
    		$attend = new StudentAttendance();
    		$attend->date = date('Y-m-d',strtotime($request->date));
    		$attend->student_id = $request->student_id[$i];
    		$attend->attend_status = $request->$attend_status;
    		$attend->save();
    	} // end For Loop

 		$notification = array(
    		'message' => 'student Attendace Data Update Successfully',
    		'alert-type' => 'success'
    	);

    	return redirect()->route('student.attendance.view')->with($notification);

    } // end Method



    public function AttendanceEdit($date){
    	$data['editData'] = StudentAttendance::where('date',$date)->get();
    	$data['students'] = User::where('usertype','student')->get();
    	return view('backend.student.student_attendance.student_attendance_edit',$data);
    }


    public function AttendanceDetails($date){
    	$data['details'] = StudentAttendance::where('date',$date)->get();
    	return view('backend.student.student_attendance.student_attendance_details',$data);

    }
}
