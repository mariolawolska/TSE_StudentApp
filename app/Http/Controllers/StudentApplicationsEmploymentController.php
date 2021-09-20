<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentApplicationsEmployment;
use App\Models\StudentApplications;
use DB;

class StudentApplicationsEmploymentController extends SecureController {

    /**
     * Add data to student_applications_employment table
     * 
     * @param Request $request
     */
    function add_data(Request $request) {

        $studentApplicationsId = \Session::get('studentApplicationsId');
        $dataArray = [];
        foreach ($request->input()['serializeArray'] as $inputArray) {


            if ($inputArray['name'] != '_token') {
                $indexRaw = explode('_', $inputArray['name']);
                $index = $indexRaw[1];
                $dataArray[$index][$indexRaw[0]] = $inputArray['value'];
            }
        }
        foreach ($dataArray as $index => $data) {

            $data = array(
                'student_applications_id' => $studentApplicationsId,
                'employer' => $data['employer'],
                'job_title' => $data['jobTitle'],
                'full_or_part_time' => $data['fullOrPartTime'],
                'employment_start_date' => $data['employmentStartDate'],
                'employment_end_date' => $data['employmentEndDate'],
                'role_description' => $data['roleDescription'],
            );

            $saq = StudentApplicationsEmployment::where('id', $index)->find($index);
            if ($saq instanceof StudentApplicationsEmployment) {
                DB::table('student_applications_employment')->where('id', $index)->update($data);
            } else {
                DB::table('student_applications_employment')->insert($data);
            }
        }

        $studentApplications = StudentApplications::getStudentApplicationsById($studentApplicationsId);
        $studentApplications->form_part = StudentApplications::FORM_PART_EIGHTH;
        $studentApplications->save();

        $this->formStageControll('next', StudentApplications::FORM_PART_EIGHTH);

        return redirect()->back();
    }

    /**
     * Delete data from student_applications_employment table
     * 
     * @param Request $request
     */
    function delete_data(Request $request) {

        if ($request->input('formId')) {
            DB::table('student_applications_employment')
                    ->where('id', $request->input('formId'))
                    ->delete();
        }
    }

}
