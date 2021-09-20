<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentApplicationsQualifications;
use App\Models\StudentApplications;
use App\Models\Thinkspace_Vle\ThinkspaceVle;
use DB;

class StudentApplicationsQualificationsController extends SecureController {

    /**
     * Method saves Qualifications to student_applications_qualifications table
     * 
     * @param Request $request
     */
    function saveQualifications(Request $request) {

        $studentApplicationsId = \Session::get('studentApplicationsId');
        $user_id = \Session::get('user_id');

        $dataArray = [];

        foreach ($request->input()['serializeArray'] as $inputArray) {

            if ($inputArray['name'] != '_token') {
                $indexRaw = explode('_', $inputArray['name']);
                $index = $indexRaw[1];
                $dataArray[$index][$indexRaw[0]] = $inputArray['value'];
            }
        }

        foreach ($dataArray as $index => $data) {
            if ($index == 'hq') {
                $data = array(
                    'highest_qualification' => $data['highestQualification'],
                    'country' => $data['country'],
                    'institution' => $data['institution'],
                    'subject' => $data['subject'],
                    'grade' => $data['grade'],
                    'examining_body' => $data['examiningBody'],
                    'setting' => $data['setting'],
                    'year' => $data['year']
                );

                StudentApplications::updateCourseDetailsForm_7($studentApplicationsId, $data);
            } else {
                $data = array(
                    'student_applications_id' => $studentApplicationsId,
                    'country' => $data['country'],
                    'institution' => $data['institution'],
                    'qualification' => $data['qualificationType'],
                    'subject' => $data['qualificationSubject'],
                    'grade' => $data['qualificationGrade'],
                    'examining_body' => $data['examiningBody'],
                    'sitting' => $data['sitting'],
                    'year' => $data['year']
                );

                $saq = StudentApplicationsQualifications::where('id', $index)->find($index);
                if ($saq instanceof StudentApplicationsQualifications) {
                    DB::table('student_applications_qualifications')->where('id', $index)->update($data);
                } else {
                    DB::table('student_applications_qualifications')->insert($data);
                }
            }
        }
        $this->formStageControll('next', StudentApplications::FORM_PART_SEVENTH);

        return redirect()->back();
    }

    /**
     * Delete data from student_applications_qualifications table
     * 
     * @param Request $request
     */
    function deleteQualification(Request $request) {

        if ($request->input('formId')) {
            DB::table('student_applications_qualifications')
                    ->where('id', $request->input('formId'))
                    ->delete();
        }
    }

    /**
     * Method checks if student's highest_qualification & previous_country 
     * are  proper to display "more qualification " button 
     * - no information about student' HighestQualification
     * 
     * @param Request $request
     * @return json
     */
    function showMoreQualification(Request $request) {

        $result = false;

        $studentApplicationsId = \Session::get('studentApplicationsId');
        $studentApplications = StudentApplications::getStudentApplicationsById($studentApplicationsId);

        $highestQualificationCode = StudentApplications::checkHighestQualificationCode($request->qualification);
        $countryCode = StudentApplications::checkCountryCode($studentApplications->previous_country);

        if ($countryCode && $highestQualificationCode) {
            $result = true;
        }

        return response()->json($result);
    }

    /**
     * Method checks if student's highest_qualification & previous_country 
     * are  proper to display "more qualification " button
     * - student saved information about HighestQualification
     * 
     * @param Request $request
     * @return json
     */
    function checkStudentHasQualifications(Request $request) {

        $studentHasQualifications = false;
        $showMoreButton = false;

        $studentApplicationsId = \Session::get('studentApplicationsId');
        $studentApplications = StudentApplications::getStudentApplicationsById($studentApplicationsId);
        $countryCode = StudentApplications::checkCountryCode($studentApplications->previous_country);
        $highestQualificationCode = StudentApplications::checkHighestQualificationCode($studentApplications->highest_qualification);
        $studentApplicationsQualificationsCollection = StudentApplicationsQualifications::getStudentApplicationQualificationByIdCollection($studentApplicationsId);
        $studentQualificationsNo = count($studentApplicationsQualificationsCollection);

        if ($studentQualificationsNo > 0) {
            $studentHasQualifications = true;
        }

        if ($highestQualificationCode && $countryCode) {
            $showMoreButton = true;
        }

        $resultArray = [$showMoreButton, $studentHasQualifications];

        return response()->json($resultArray);
    }

}
