<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thinkspace_Vle\ThinkspaceVle;
use App\Models\StudentApplications;
use App\Models\StudentApplicationsQualifications;
use App\Models\StudentApplicationsEmployment;
use Illuminate\Support\Facades\Mail;

class StudentApplicationsController extends SecureController {

    const NUMBER_FOR_SEPTEMBER = 9;
    const ACADEMIC_YEAR_RADIO_OPTIONS = 3;

    public $form_stage = [
        'stage_no' => 0,
        'steps_available' => [],
        'steps_name' => [],
        'validation' => false,
        'show_back' => false,
        'show_next' => false,
    ];

    public function studentApplicationStart($token) {
        \Session::flush();
        \Session::put('form_stage', $this->form_stage);

        if ($this->checkTokenForUser($token)) {
            return redirect('/student/application/');
        } else {
            echo 'Sorry, there was an error, please refresh this page';
        }
    }

    public function studentapplication() {
        if ((\Session::has('form_stage'))) {

            $this->deleteTokenForUser();

            return $this->displayStudentApplicationForm();
        } else {
            echo 'Sorry, there was an error, please refresh this page';
        }
    }

    /**
     * @return array
     */
    private function getAcademicYearOfEntry(): array {

        $startAcademicYear = 0;
        $presentDateT = date('Y-m-d');
        if (date('n') > self::NUMBER_FOR_SEPTEMBER) {
            $presentYear = date('Y-m-d', strtotime('+1 Year', strtotime($presentDateT)));
        } else {
            $presentYear = $presentDateT;
        }

        $academicYearOfEntry = [];

        while ($startAcademicYear < self::ACADEMIC_YEAR_RADIO_OPTIONS) {
            $datePlus = date('Y', strtotime('+' . $startAcademicYear . ' Year', strtotime($presentYear)));
            $academicYearOfEntry[$datePlus] = $datePlus;
            $startAcademicYear++;
        }

        return $academicYearOfEntry;
    }

    /**
     * @return array
     */
    private function getMonthOfEntry(): array {

        return $academicMonthOfEntry = [
            1 => 'January',
            2 => 'February'
        ];
    }

    /**
     * @return view
     */
    private function displayStudentApplicationForm() {

        $userId = $this->getUserId();
        switch ($this->getFormStageStageNo()) {
            case 1:
                return $this->applicationFormPart_1($userId);
                break;
            case 2:
                return $this->applicationFormPart_2($userId);
                break;
            case 3:
                return $this->applicationFormPart_3($userId);
                break;
            case 4:
                return $this->applicationFormPart_4($userId);
                break;
            case 5:
                return $this->applicationFormPart_5($userId);
                break;
            case 6:
                return $this->applicationFormPart_6($userId);
                break;
            case 7:
                return $this->applicationFormPart_7($userId);
                break;
            case 8:
                return $this->applicationFormPart_8($userId);
                break;
            case 9:
                return $this->applicationForm_Summary($userId);
                break;
        }
    }

    /**
     * Part 1: Course Details            
     * 
     * @return view
     */
    private function applicationFormPart_1() {

        $studentApplicationsId = $this->getStudentApplicationsId();
        $studentApplicationsObject = StudentApplications::getStudentApplicationsById($studentApplicationsId);

        $nameOfCourseCollect = ThinkspaceVle::getNameOfCourse();
        $qualificationType = StudentApplications::getQualificationTypes();
        $academicYearOfEntry = $this->getAcademicYearOfEntry();
        $monthOfEntry = $this->getMonthOfEntry();

        $modeOfStudyMA = ThinkspaceVle::getModeOfStudyTypeMa();
        $modeOfStudyMAIdArray = ThinkspaceVle::getModeOfStudyTypeMaIdArray($modeOfStudyMA);
        $modeOfStudyMFA = ThinkspaceVle::getModeOfStudyTypeMfa();
        $modeOfStudyMFAIdArray = ThinkspaceVle::getModeOfStudyTypeMfaIdArray($modeOfStudyMFA);

        $modeOfStudyMA_Array = StudentApplications::getModeOfStudy('MA');
        $modeOfStudyMFA_Array = StudentApplications::getModeOfStudy('MFA');

        return view('studentaplication.student-application-form-1', [
            'nameOfCourseCollect' => $nameOfCourseCollect,
            'qualificationType' => $qualificationType,
            'academicYearOfEntry' => $academicYearOfEntry,
            'monthOfEntry' => $monthOfEntry,
            'modeOfStudyMA' => $modeOfStudyMA,
            'modeOfStudyMAIdArray' => $modeOfStudyMAIdArray,
            'modeOfStudyMFA' => $modeOfStudyMFA,
            'modeOfStudyMFAIdArray' => $modeOfStudyMFAIdArray,
            'studentApplicationsObject' => $studentApplicationsObject,
            'modeOfStudyMA_Array' => $modeOfStudyMA_Array,
            'modeOfStudyMFA_Array' => $modeOfStudyMFA_Array,
        ]);
    }

    /**
     * Part 2: Personal Details      
     * 
     * @return view
     */
    private function applicationFormPart_2() {

        $studentApplicationsId = $this->getStudentApplicationsId();
        $studentApplicationsObject = StudentApplications::getStudentApplicationsById($studentApplicationsId);

        $titleArray = $this->checkTitle($studentApplicationsObject->title);

        $titles = StudentApplications::getTitle();

        $studentDomicile = \App\Models\Hesa\StudentAlternative\Domicile::getStudentDomicile();
        $studentEthnic = \App\Models\Hesa\StudentAlternative\Ethnic::getStudentEthnic();

        $studentGenderid = \App\Models\Hesa\Student\Genderid::getStudentGenderid();
        $studentNation = \App\Models\Hesa\Student\Nation::getStudentNation();

        $studentReligion = \App\Models\Hesa\StudentAlternative\Relblf::getStudentReligion();
        $studentSexid = \App\Models\Hesa\Student\Sexid::getStudentSexid();
        $studentSexort = \App\Models\Hesa\Student\Sexort::getStudentSexort();

        return view('studentaplication.student-application-form-2', [
            'titles' => $titles,
            'titleArray' => $titleArray,
            'studentDomicile' => $studentDomicile,
            'studentEthnic' => $studentEthnic,
            'studentGenderid' => $studentGenderid,
            'studentNation' => $studentNation,
            'studentReligion' => $studentReligion,
            'studentSexid' => $studentSexid,
            'studentSexort' => $studentSexort,
            'studentApplicationsObject' => $studentApplicationsObject
        ]);
    }

    /**
     * Part 3: Disabilities & Accessibility
     * 
     * @return view
     */
    private function applicationFormPart_3() {

        $studentApplicationsId = $this->getStudentApplicationsId();
        $studentApplicationsObject = StudentApplications::getStudentApplicationsById($studentApplicationsId);

        $studentDisable = \App\Models\Hesa\StudentAlternative\Disable::getStudentDisable();
        $requireAdjustments = StudentApplications::getRequireAdjustments();

        return view('studentaplication.student-application-form-3', [
            'studentDisable' => $studentDisable,
            'requireAdjustments' => $requireAdjustments,
            'studentApplicationsObject' => $studentApplicationsObject
        ]);
    }

    /**
     * Part 4: Further Information  
     * 
     * @return view
     */
    private function applicationFormPart_4() {
        $studentApplicationsId = $this->getStudentApplicationsId();
        $studentApplicationsObject = StudentApplications::getStudentApplicationsById($studentApplicationsId);

        $studentMstufee = \App\Models\Hesa\StudentAlternative\Mstufee::getStudentMstufee();
        $typeWorkInIndustryArray = StudentApplications::getTypeWorkInIndustry();
        $requireAdjustments = StudentApplications::getRequireAdjustments();
        $currentCountry = StudentApplications::checkCountrie($studentApplicationsId);

        return view('studentaplication.student-application-form-4', [
            'studentMstufee' => $studentMstufee,
            'typeWorkInIndustryArray' => $typeWorkInIndustryArray,
            'requireAdjustments' => $requireAdjustments,
            'studentApplicationsObject' => $studentApplicationsObject,
            'currentCountry' => $currentCountry
        ]);
    }

    /**
     * Part 5: Further Information  
     * 
     * @return view
     */
    private function applicationFormPart_5() {

        $studentApplicationsId = $this->getStudentApplicationsId();
        $studentApplicationsObject = StudentApplications::getStudentApplicationsById($studentApplicationsId);

        $requireAdjustments = StudentApplications::getRequireAdjustments();

        return view('studentaplication.student-application-form-5', [
            'requireAdjustments' => $requireAdjustments,
            'studentApplicationsObject' => $studentApplicationsObject
        ]);
    }

    /**
     * Part 6: Hardware & Equipment 
     * 
     * @return view
     */
    private function applicationFormPart_6() {

        $studentApplicationsId = $this->getStudentApplicationsId();
        $studentApplicationsObject = StudentApplications::getStudentApplicationsById($studentApplicationsId);

        return view('studentaplication.student-application-form-6', [
            'studentApplicationsObject' => $studentApplicationsObject
        ]);
    }

    /**
     * Part 7: Qualifications
     * 
     * @return type
     */
    private function applicationFormPart_7() {

        $studentApplicationsId = $this->getStudentApplicationsId();
        $studentApplicationsObject = StudentApplications::getStudentApplicationsById($studentApplicationsId);
        $studentApplicationsQualificationsCollection = StudentApplicationsQualifications::getStudentApplicationQualificationByIdCollection($studentApplicationsId);
        $studentApplicationQualification = StudentApplicationsQualifications::getStudentApplicationQualificationById($studentApplicationsId);
        $studenAlternativeQualification = \App\Models\Hesa\StudentAlternative\Qualent3::getStudenAlternativeQualification();
        $settingArray = StudentApplications::getSitting();
        $studenAlternativeQualtype = \App\Models\Hesa\StudentAlternative\Qualtype::getStudenAlternativeQualtype();
        $studenAlternativeQualsbj = \App\Models\Hesa\StudentAlternative\Qualsbj::getStudenAlternativeQualsbj();
        $studenAlternativeQualgrade = \App\Models\Hesa\StudentAlternative\Qualgrade::getStudenAlternativeQualgrade();
        $selectBoxQualtype = $this->fillSelectBox($studenAlternativeQualtype);
        $selectBoxQualsbj = $this->fillSelectBox($studenAlternativeQualsbj);
        $selectBoxQualgrade = $this->fillSelectBox($studenAlternativeQualgrade);

        return view('studentaplication.student-application-form-7', [
            'studenAlternativeQualification' => $studenAlternativeQualification,
            'settingArray' => $settingArray,
            'selectBoxQualtype' => $selectBoxQualtype,
            'selectBoxQualsbj' => $selectBoxQualsbj,
            'selectBoxQualgrade' => $selectBoxQualgrade,
            'studenAlternativeQualtype' => $studenAlternativeQualtype,
            'studenAlternativeQualsbj' => $studenAlternativeQualsbj,
            'studenAlternativeQualgrade' => $studenAlternativeQualgrade,
            'studentApplicationsQualificationsCollection' => $studentApplicationsQualificationsCollection,
            'studentApplicationsObject' => $studentApplicationsObject,
            'studentApplicationQualification' => $studentApplicationQualification,
        ]);
    }

    /**
     * Part 8: Employment History
     * 
     * @return type
     */
    private function applicationFormPart_8() {

        $studentApplicationsId = $this->getStudentApplicationsId();
        $studentApplicationsEmploymentCollection = StudentApplicationsEmployment::getStudentApplicationsEmploymentByIdCollection($studentApplicationsId);

        return view('studentaplication.student-application-form-8', [
            'studentApplicationsEmploymentCollection' => $studentApplicationsEmploymentCollection,
            'studentApplicationsId' => $studentApplicationsId,
        ]);
    }

    /**
     * Part 9: Summary
     * 
     * @return type
     */
    private function applicationForm_Summary() {

        $studentApplicationsId = $this->getStudentApplicationsId();
        $studentApplicationsObject = StudentApplications::getStudentApplicationsById($studentApplicationsId);

        return view('studentaplication.student-application-form-summary', [
            'studentApplicationsObject' => $studentApplicationsObject,
        ]);
    }

    /**
     * @param string $studentTitle
     * 
     * @return array
     */
    private function checkTitle($studentTitle = ''): array {

        $returnArray = [
            'title' => '',
            'other' => ''
        ];
        if (str_contains($studentTitle, 'Other')) {
            $studentTitleArray = explode('##', $studentTitle);
            $returnArray['title'] = $studentTitleArray[0];
            $returnArray['other'] = $studentTitleArray[1];
        } else {
            $returnArray['title'] = $studentTitle;
        }
        return $returnArray;
    }

    /**
     * @param type $obj
     * @return string
     */
    public function fillSelectBox($obj) {

        $output = '';

        foreach ($obj as $row) {
            $output .= '<option value="' . $row["hesa_code"] . '">' . $row["hesa_label"] . '</option>';
        }

        $output = str_replace("'", '&#39;', $output);
        return $output;
    }

    /**
     * Student Application
     * 
     * @param Request $request
     * 
     * @return type
     */
    public function saveStudentApplication(Request $request) {


        $studentApplicationsId = $this->getStudentApplicationsId();

        switch ($this->getFormStageStageNo()) {
            case 1:
                $this->saveApplicationForm_1($studentApplicationsId, $request);
                break;
            case 2:
                $this->saveApplicationForm_2($studentApplicationsId, $request);
                break;
            case 3:
                $this->saveApplicationForm_3($studentApplicationsId, $request);
                break;
            case 4:
                $this->saveApplicationForm_4($studentApplicationsId, $request);
                break;
            case 5:
                $this->saveApplicationForm_5($studentApplicationsId, $request);
                break;
            case 6:
                $this->saveApplicationForm_6($studentApplicationsId, $request);
                break;
            case 7:
                $this->saveApplicationForm_7($studentApplicationsId, $request);
                break;
            case 8:
                $this->saveApplicationForm_8($studentApplicationsId, $request);
                break;
        }


        return redirect()->back();
    }

    /**
     * @param type $studentApplicationsId
     * @param type $request
     * 
     * @return redirect
     */
    private function saveApplicationForm_1($studentApplicationsId, $request) {

        $userId = \Session::get('user_id');

        $studentApplicationsId = StudentApplications::setupCourseDetailsForm_1($userId, $studentApplicationsId, $request->input());
        \Session::put('studentApplicationsId', $studentApplicationsId);

        $this->formStageControll('next', StudentApplications::FORM_PART_FIRST);

        return redirect()->back();
    }

    /**
     * @param type $studentApplicationsId
     * @param type $request
     * 
     * @return redirect
     */
    private function saveApplicationForm_2($studentApplicationsId, $request) {

        StudentApplications::setupCourseDetailsForm_2($studentApplicationsId, $request->input());
        $this->formStageControll('next', StudentApplications::FORM_PART_SECOND);

        return redirect()->back();
    }

    /**
     * @param type $studentApplicationsId
     * @param type $request
     * 
     * @return redirect
     */
    private function saveApplicationForm_3($studentApplicationsId, $request) {


        $request['disabilities'] = $this->processingDisabilities($request->input());

        $currentCountry = StudentApplications::checkCountrie($studentApplicationsId);

        if ($currentCountry == 'UK') {
            $this->validate($request, [
                'disabilities' => 'required',
            ]);
        }

        StudentApplications::setupCourseDetailsForm_3($studentApplicationsId, $request->input());
        $this->formStageControll('next', StudentApplications::FORM_PART_THIRD);

        return redirect()->back();
    }

    /**
     * @param type $studentApplicationsId
     * @param type $request
     * 
     * @return redirect
     */
    private function saveApplicationForm_4($studentApplicationsId, $request) {

        StudentApplications::setupCourseDetailsForm_4($studentApplicationsId, $request->input());
        $this->formStageControll('next', StudentApplications::FORM_PART_FOURTH);

        return redirect()->back();
    }

    /**
     * @param type $studentApplicationsId
     * @param type $request
     * 
     * @return redirect
     */
    private function saveApplicationForm_5($studentApplicationsId, $request) {

        StudentApplications::setupCourseDetailsForm_5($studentApplicationsId, $request->input());
        $this->formStageControll('next', StudentApplications::FORM_PART_FIFTH);

        return redirect()->back();
    }

    /**
     * @param type $studentApplicationsId
     * @param type $request
     * 
     * @return redirect
     */
    private function saveApplicationForm_6($studentApplicationsId, $request) {

        StudentApplications::setupCourseDetailsForm_6($studentApplicationsId, $request->input());
        $this->formStageControll('next', StudentApplications::FORM_PART_SIXTH);

        return redirect()->back();
    }

    /**
     * @param type $input
     * 
     * @return type
     */
    private function processingDisabilities($input) {
        $returnArray = [];
        foreach ($input as $key => $value) {
            if (str_contains($key, 'disabilities_')) {
                $returnArray[] = explode('_', $key)[1];
            }
        }

        return implode(',', $returnArray);
    }

    public function confirmSaveForm($studentApplicationsId = '') {

        $studentApplications = StudentApplications::getStudentApplicationsById($studentApplicationsId);

        $studentApplications->form_part = StudentApplications::FORM_PART_NINTH;


        //part1
        $courseTitle = ThinkspaceVle::getTitleOfCourse($studentApplications->course_group_id);

        $data = [
            'subject' => 'ThinkSpace Education - Confirmation Email',
            'email' => $studentApplications->email,
            'studentApplications' => $studentApplications,
            'courseTitle' => $courseTitle,
        ];

        Mail::send('studentaplication.formBits.email-template', $data, function($message) use ($data) {
            $message->to($data['email'])
                    ->subject($data['subject']);
        });

        $returnValue = 0;
        if ($studentApplications->save()) {
            \Session::flush();

            return view('studentaplication.student-application-thank-you')->with(['message' => 'Student Application successfully submitted.']);
        }

        return $returnValue;
    }

    /**
     * Method checks if student's current country is UK
     * 
     * @return json
     */
    function checkCurrentCountryIsUK() {

        $result = false;

        $studentApplicationsId = \Session::get('studentApplicationsId');
        $studentApplications = StudentApplications::getStudentApplicationsById($studentApplicationsId);
        $countryCode = StudentApplications::checkCountryCode($studentApplications->current_country);

        if ($countryCode) {
            return $result = true;
        }

        return response()->json($result);
    }

    /**
     * Method checks if  student reported disability to form
     * 
     * @return json
     */
    function checkStudentHasDisabilities() {

        $result = false;

        $studentApplicationsId = \Session::get('studentApplicationsId');
        $studentApplications = StudentApplications::getStudentApplicationsById($studentApplicationsId);
        $studentApplications->disabilities;

        if (!empty($studentApplications->disabilities)) {
            return $result = true;
        }

        return response()->json($result);
    }

}
