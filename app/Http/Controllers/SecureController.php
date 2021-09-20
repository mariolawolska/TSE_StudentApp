<?php

namespace App\Http\Controllers;

use App\Models\StudentApplications;

class SecureController extends Controller {

    public function saveInSession($key, $var) {
        \Illuminate\Session::put($key, $var);
    }

    /**
     * @param string $move
     * 
     * @return bool
     */
    public function formStageControll(string $move): bool {
        $stage = false;

        $studentApplicationsId = $this->getStudentApplicationsId();
        $studentApplicationsObject = StudentApplications::getStudentApplicationsById($studentApplicationsId);

        $form_stage = \Session::get('form_stage');

        if ($studentApplicationsObject->form_part) {

            for ($i = 1; $i <= $studentApplicationsObject->form_part; $i++) {
                $form_stage['steps_available'][$i] = $i;
            }
        }

        if ($move == 'next') {
            $form_stage['stage_no'] ++;
        } else {
            $form_stage['stage_no'] --;
        }

        $form_stage['show_back'] = false;
        if ($form_stage['stage_no'] > 1) {
            $form_stage['show_back'] = true;
        }

        $form_stage['show_next'] = false;
        if (
                ($form_stage['stage_no'] < 9 && array_key_exists($form_stage['stage_no'], $form_stage['steps_available']))
        ) {
            $form_stage['show_next'] = true;
        }

        \Session::put('form_stage', $form_stage);
        return $stage;
    }

    public function setStepsAvailable($form_part) {


        $form_stage = \Session::get('form_stage');
        for ($i = 1; $i <= $form_part; $i++) {
            $form_stage['steps_available'][$i] = $i;
        }

        \Session::put('form_stage', $form_stage);
    }

    /**
     * Method sets Steps Name in session
     */
    public function setStepsName() {

        $form_stage = \Session::get('form_stage');

        $form_stage['steps_name'] = [
            1 => 'Course Details',
            2 => 'Personal Details',
            3 => 'Disabilities & Accessibility',
            4 => 'Further Information',
            5 => 'Further Information',
            6 => 'Hardware & Equipment',
            7 => 'Qualifications',
            8 => 'Employment History',
            9 => 'Confirmation',
        ];
        \Session::put('form_stage', $form_stage);
    }

    /**
     * @return int
     */
    public function getStudentApplicationsId(): int {
        $studentApplicationsId = 0;
        if (\Session::has('studentApplicationsId')) {
            $studentApplicationsId = \Session::get('studentApplicationsId');
        }
        return $studentApplicationsId;
    }

    /**
     * @return int
     */
    public function setStudentApplicationsId($applicationsId = 0) {
        \Session::put('studentApplicationsId', $applicationsId);
    }

    /**
     * @return int
     */
    public function getUserCountry(): string {
        $userCountry = 0;
        if (\Session::has('user_country')) {
            $userCountry = \Session::get('user_country');
        }
        return $userCountry;
    }

    /**
     * @return int
     */
    public function getUserId(): int {
        $user_id = 0;
        if (\Session::has('user_id')) {
            $user_id = \Session::get('user_id');
        }
        return $user_id;
    }

    /**
     * @return int
     */
    public function getFormStageStageNo(): int {
        $formStageStageNo = 0;
        if (\Session::has('form_stage')) {
            $form_stage = \Session::get('form_stage');
            $formStageStageNo = $form_stage['stage_no'];
        }
        return $formStageStageNo;
    }

    /**
     * Check Token For User
     * 
     * @param string $token
     * @param bool $end
     * 
     * @return array
     */
    public function checkTokenForUser(string $token = ''): bool {
        \Session::forget('user_id');

        $laravelToken = false;
        $laravelUserForToken = \App\Models\Thinkspace_Vle\LaravelToken::getLaravelTokenForUser($token);

        if (!empty($laravelUserForToken->token)) {

            $laravelToken = true;
            \Session::put('user_id', $laravelUserForToken->user_id);
            \Session::put('user_token', $token);
        }

        return $laravelToken;
    }

    public function deleteTokenForUser() {

        $token = '';
        if (\Session::has('user_token')) {
            $token = \Session::get('user_token');
        }

        $laravelUserForToken = \App\Models\Thinkspace_Vle\LaravelToken::getLaravelTokenForUser($token);

        if (!empty($laravelUserForToken->token)) {

            /**
             * Check if Student Applications Exists For The User Id
             */
            $studentApplications = \App\Models\StudentApplications::getStudentApplicationsUserById($laravelUserForToken->user_id);

            if ($studentApplications && $studentApplications->form_part != StudentApplications::FORM_PART_NINTH) {
                $this->setStudentApplicationsId($studentApplications->id);
                $this->setStepsAvailable($studentApplications->form_part);
                $this->setStepsName();
                $this->formStageControll('next');
            } else {
                $this->formStageControll('next');
                $this->setStudentApplicationsId(0);
                $this->setStepsName();
                $studentApplications = new StudentApplications();
            }

            /**
             * Delete Token For User
             */
            \App\Models\Thinkspace_Vle\LaravelToken::deleteLaravelTokenForUser($laravelUserForToken->user_id);
        }
    }

}
