<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentApplications extends Model {

    use HasFactory;

    const FORM_PART_FIRST = 1;
    const FORM_PART_SECOND = 2;
    const FORM_PART_THIRD = 3;
    const FORM_PART_FOURTH = 4;
    const FORM_PART_FIFTH = 5;
    const FORM_PART_SIXTH = 6;
    const FORM_PART_SEVENTH = 7;
    const FORM_PART_EIGHTH = 8;
    const FORM_PART_NINTH = 99;
    //
    const UNSET_VALUE_FOR_DROPTDOWN = 3;

    protected $table = 'student_applications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'qualification_type',
        'academic_year_of_entry',
        'month_of_entry',
        'mode_of_study',
        'surname',
        'first_names',
        'preferred_name',
        'previous_name',
        'title',
        'dob',
        'native_language',
        'home_address',
        'home_postcode',
        'current_country',
        'previous_country',
        'contact_number',
        'email',
        'correspondence_address',
        'correspondence_postcode',
        'emergency_contact',
        'emergency_phone',
        'emergency_email',
        'ethnicity',
        'gender',
        'nationality',
        'religion',
        'sex_identifier',
        'sexual_orientation',
        'disabilities',
        'learning_disabilities',
        'require_adjustments',
        'disability_allowance',
        'require_appointmnet',
        'resident_abroad_in_last_10_years',
        'resident_abroad_details',
        'date_first_entry_to_uk',
        'who_will_pay_fees',
        'work_in_industry',
        'work_in_industry_type',
        'tax_vat_number',
        'need_payment_plan',
        'need_student_loan',
        'student_loan_critical',
        'have_completed_first_degree_in_english',
        'sat_english_language_test',
        'sat_english_language_test_details',
        'sat_english_language_test_date',
        'sat_english_language_test_result',
        'sat_english_language_test_more_info',
        'primary_computer',
        'slave_computer',
        'primary_computer_processor',
        'primary_computer_ram',
        'primary_computer_gpu',
        'primary_computer_hard_disks',
        'primary_computer_os',
        'primary_computer_internet',
        'primary_computer_daw',
        'primary_computer_sample_libraries',
        'primary_computer_monitors',
        'primary_computer_audio_interface',
        'primary_computer_recording_equipment',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_course_lookup',
        'course_group_id',
        'form_part',
    ];

    /**
     * @param type $id
     * 
     * @return boolean|\App\Models\StudentApplications
     */
    public static function getStudentApplicationsById($id) {

        $studentApplications = StudentApplications::where('id', '=', $id)->first();
        if ($studentApplications instanceof StudentApplications) {
            return $studentApplications;
        } else {
            return new StudentApplications();
        }
    }

    /**
     * @param type $userId
     * 
     * @return boolean|\App\Models\StudentApplications
     */
    public static function getStudentApplicationsUserById($userId) {

        $studentApplications = StudentApplications::where('user_id', '=', $userId)->orderBy('id', 'DESC')->first();
        if ($studentApplications instanceof StudentApplications) {
            return $studentApplications;
        } else {
            return false;
        }
    }

    /**
     * @param type $userId
     * @param type $studentApplicationsId
     * @param type $input
     * 
     * @return type
     */
    public static function setupCourseDetailsForm_1($userId, $studentApplicationsId, $input) {

        if ($studentApplicationsId) {
            return self::updateCourseDetailsForm_1($studentApplicationsId, $input);
        } else {
            return self::saveCourseDetailsForm_1($userId, $input);
        }
    }

    /**
     * Check Form Part Number
     * @param type $form_part
     * @return boolean
     */
    public static function checkFormPart($userId, $form_part) {

        $update = false;

        $studentApplicationsObject = StudentApplications::getStudentApplicationsById($userId);
        $storedFormPart = $studentApplicationsObject->form_part;

        if ($storedFormPart < $form_part) {
            $update = true;
        }
        return $update;
    }

    /**
     * @param int $userId
     * @param array $input
     * 
     * @return int
     */
    public static function saveCourseDetailsForm_1(int $userId, array $input): int {

        $studentApplications = new StudentApplications();
        $studentApplications->user_id = $userId;
        $studentApplications->course_group_id = $input['course_group'];
        $studentApplications->qualification_type = $input['qualification_type'] ?? '';
        $studentApplications->academic_year_of_entry = $input['academic_year_of_entry'];
        $studentApplications->month_of_entry = $input['month_of_entry'];
        $studentApplications->mode_of_study = $input['mode_of_study'];
        $studentApplications->form_part = self::FORM_PART_FIRST;

        $studentApplications->learning_disabilities = self::UNSET_VALUE_FOR_DROPTDOWN;
        $studentApplications->require_adjustments = self::UNSET_VALUE_FOR_DROPTDOWN;
        $studentApplications->disability_allowance = self::UNSET_VALUE_FOR_DROPTDOWN;
        $studentApplications->require_appointmnet = self::UNSET_VALUE_FOR_DROPTDOWN;


        $returnValue = 0;
        if ($studentApplications->save()) {
            $returnValue = $studentApplications->id;
        }
        return $returnValue;
    }

    /**
     * @param int $studentApplicationsId
     * @param array $input
     * 
     * @return int
     */
    public static function updateCourseDetailsForm_1(int $studentApplicationsId, array $input): int {
        $studentApplications = self::getStudentApplicationsById($studentApplicationsId);
        $studentApplications->course_group_id = $input['course_group'];
        $studentApplications->qualification_type = $input['qualification_type'] ?? '';
        $studentApplications->academic_year_of_entry = $input['academic_year_of_entry'];
        $studentApplications->month_of_entry = $input['month_of_entry'];
        $studentApplications->mode_of_study = $input['mode_of_study'];

        $returnValue = 0;
        if ($studentApplications->save()) {
            $returnValue = $studentApplications->id;
        }
        return $returnValue;
    }

    /**
     * @param type $userId
     * @param type $studentApplicationsId
     * @param type $input
     * 
     * @return type
     */
    public static function setupCourseDetailsForm_2($studentApplicationsId, $input) {

        if ($studentApplicationsId) {
            return self::updateCourseDetailsForm_2($studentApplicationsId, $input);
        }
    }

    /**
     * @param int $studentApplicationsId
     * @param array $input
     * 
     * @return int
     */
    public static function updateCourseDetailsForm_2(int $studentApplicationsId, array $input): int {

        $studentApplications = self::getStudentApplicationsById($studentApplicationsId);
        $studentApplications->surname = $input['surname'];
        $studentApplications->first_names = $input['first_names'];
        $studentApplications->preferred_name = $input['preferred_name'];
        $studentApplications->previous_name = $input['previous_name'];
        $title = $input['title'];
        if ($title == 'Other') {
            $title = $input['title'] . '##' . $input['title_other'];
        }
        $studentApplications->title = $title ?? '';
        $studentApplications->dob = $input['dob'];
        $studentApplications->native_language = $input['native_language'];
        $studentApplications->home_address = $input['home_address'];
        $studentApplications->home_postcode = $input['home_postcode'];
        $studentApplications->current_country = $input['current_country'];

        $studentApplications->previous_country = $input['previous_country'];
        $studentApplications->contact_number = $input['contact_number'];
        $studentApplications->email = $input['email'];
        $studentApplications->correspondence_address = $input['correspondence_address'];
        $studentApplications->correspondence_postcode = $input['correspondence_postcode'];

        $studentApplications->emergency_contact = $input['emergency_contact'];
        $studentApplications->emergency_phone = $input['emergency_phone'];
        $studentApplications->emergency_email = $input['emergency_email'];
        $studentApplications->ethnicity = $input['ethnicity'] ?? '';
        $studentApplications->gender = $input['gender'] ?? '';

        $studentApplications->nationality = $input['nationality'] ?? '';
        $studentApplications->religion = $input['religion'] ?? '';
        $studentApplications->sex_identifier = $input['sex_identifier'] ?? '';
        $studentApplications->sexual_orientation = $input['sexual_orientation'] ?? '';

        $formPartNo = self::checkFormPart($studentApplicationsId, self::FORM_PART_SECOND);
        if ($formPartNo) {
            $studentApplications->form_part = self::FORM_PART_SECOND;
        }

        $returnValue = 0;
        if ($studentApplications->save()) {
            $returnValue = $studentApplications->id;
        }
        return $returnValue;
    }

    /**
     * @param int $studentApplicationsId
     * @param array $input
     * 
     * @return int
     */
    public static function updateCourseDetailsForm_3($studentApplicationsId, array $input) {

        $studentApplications = self::getStudentApplicationsById($studentApplicationsId);

        $studentApplications->disabilities = $input['disabilities'] ?? '';
        $studentApplications->learning_disabilities = $input['learning_disabilities'];
        $studentApplications->require_adjustments = $input['require_adjustments'];
        $studentApplications->disability_allowance = $input['disability_allowance'];
        $studentApplications->require_appointmnet = $input['require_appointmnet'];

        $formPartNo = self::checkFormPart($studentApplicationsId, self::FORM_PART_THIRD);
        if ($formPartNo) {
            $studentApplications->form_part = self::FORM_PART_THIRD;
        }

        $returnValue = 0;
        if ($studentApplications->save()) {
            $returnValue = $studentApplications->id;
        }
        return $returnValue;
    }

    /**
     * @param type $userId
     * @param type $studentApplicationsId
     * @param type $input
     * 
     * @return type
     */
    public static function setupCourseDetailsForm_3($studentApplicationsId, $input) {
        if ($studentApplicationsId) {
            return self::updateCourseDetailsForm_3($studentApplicationsId, $input);
        }
    }

    /**
     * @param int $studentApplicationsId
     * @param array $input
     * 
     * @return int
     */
    public static function updateCourseDetailsForm_4($studentApplicationsId, array $input) {

        $studentApplications = self::getStudentApplicationsById($studentApplicationsId);
        $studentApplications->resident_abroad_in_last_10_years = $input['resident_abroad_in_last_10_years'] ?? '';
        $studentApplications->resident_abroad_details = $input['resident_abroad_details'] ?? '';
        $studentApplications->date_first_entry_to_uk = $input['date_first_entry_to_uk'] ?? '';

        $studentApplications->who_will_pay_fees = $input['who_will_pay_fees'];
        /**
         * work_in_industry
         */
        $work_in_industry = $input['work_in_industry'];
        $studentApplications->work_in_industry = $work_in_industry;
        /**
         * $work_in_industry_type
         */
        $work_in_industry_type = $work_in_industry == 'No' ? '' : ($input['work_in_industry_type'] ?? '');
        $studentApplications->work_in_industry_type = $work_in_industry_type;
        /**
         * tax_vat_number 
         */
        $studentApplications->tax_vat_number = $work_in_industry_type == 'Self-Employed' ? $input['tax_vat_number'] : '';

        $studentApplications->need_payment_plan = $input['need_payment_plan'];
        $studentApplications->need_student_loan = $input['need_student_loan'] ?? '';

        $studentApplications->student_loan_critical = $input['student_loan_critical'] ?? '';

        $formPartNo = self::checkFormPart($studentApplicationsId, self::FORM_PART_FOURTH);
        if ($formPartNo) {
            $studentApplications->form_part = self::FORM_PART_FOURTH;
        }

        $returnValue = 0;
        if ($studentApplications->save()) {
            $returnValue = $studentApplications->id;
        }
        return $returnValue;
    }

    /**
     * @param type $userId
     * @param type $studentApplicationsId
     * @param type $input
     * 
     * @return type
     */
    public static function setupCourseDetailsForm_4($studentApplicationsId, $input) {
        if ($studentApplicationsId) {
            return self::updateCourseDetailsForm_4($studentApplicationsId, $input);
        }
    }

    /**
     * @param int $studentApplicationsId
     * @param array $input
     * 
     * @return int
     */
    public static function updateCourseDetailsForm_5($studentApplicationsId, array $input) {

        $studentApplications = self::getStudentApplicationsById($studentApplicationsId);

        /**
         * have_completed_first_degree_in_english
         */
        $have_completed_first_degree_in_english = $input['have_completed_first_degree_in_english'];
        $studentApplications->have_completed_first_degree_in_english = $have_completed_first_degree_in_english;

        /**
         * sat_english_language_test
         */
        $sat_english_language_test = $have_completed_first_degree_in_english == 'No' ? '' : ($input['sat_english_language_test'] ?? '');
        $studentApplications->sat_english_language_test = $sat_english_language_test;
        /**
         * sat_english_language_test_date
         */
        $sat_english_language_test_date = $sat_english_language_test == 'No' || $have_completed_first_degree_in_english == 'No' ? '' : ($input['sat_english_language_test_date'] ?? '');
        $studentApplications->sat_english_language_test_date = $sat_english_language_test_date;

        /**
         * sat_english_language_test_details
         */
        $sat_english_language_test_details = $sat_english_language_test == 'No' || $have_completed_first_degree_in_english == 'No' ? '' : ($input['sat_english_language_test_details'] ?? '');
        $studentApplications->sat_english_language_test_details = $sat_english_language_test_details;

        /**
         * sat_english_language_test_details
         */
        $sat_english_language_test_result = $sat_english_language_test == 'No' || $have_completed_first_degree_in_english == 'No' ? '' : ($input['sat_english_language_test_result'] ?? '');
        $studentApplications->sat_english_language_test_result = $sat_english_language_test_result;

        $studentApplications->sat_english_language_test_more_info = $input['sat_english_language_test_more_info'];


        $formPartNo = self::checkFormPart($studentApplicationsId, self::FORM_PART_FIFTH);
        if ($formPartNo) {
            $studentApplications->form_part = self::FORM_PART_FIFTH;
        }

        $returnValue = 0;
        if ($studentApplications->save()) {
            $returnValue = $studentApplications->id;
        }
        return $returnValue;
    }

    /**
     * @param type $userId
     * @param type $studentApplicationsId
     * @param type $input
     * 
     * @return type
     */
    public static function setupCourseDetailsForm_5($studentApplicationsId, $input) {
        if ($studentApplicationsId) {
            return self::updateCourseDetailsForm_5($studentApplicationsId, $input);
        }
    }

    /**
     * @param int $studentApplicationsId
     * @param array $input
     * 
     * @return int
     */
    public static function updateCourseDetailsForm_6($studentApplicationsId, array $input) {

        $studentApplications = self::getStudentApplicationsById($studentApplicationsId);
        $studentApplications->primary_computer = $input['primary_computer'] ?? '';
        $studentApplications->slave_computer = $input['slave_computer'] ?? '';
        $studentApplications->primary_computer_processor = $input['primary_computer_processor'] ?? '';

        $studentApplications->primary_computer_ram = $input['primary_computer_ram'] ?? '';
        $studentApplications->primary_computer_gpu = $input['primary_computer_gpu'] ?? '';
        $studentApplications->primary_computer_hard_disks = $input['primary_computer_hard_disks'] ?? '';
        $studentApplications->primary_computer_os = $input['primary_computer_os'] ?? '';

        $studentApplications->primary_computer_internet = $input['primary_computer_internet'] ?? '';
        $studentApplications->primary_computer_daw = $input['primary_computer_daw'] ?? '';
        $studentApplications->primary_computer_sample_libraries = $input['primary_computer_sample_libraries'] ?? '';
        $studentApplications->primary_computer_monitors = $input['primary_computer_monitors'] ?? '';

        $studentApplications->primary_computer_audio_interface = $input['primary_computer_audio_interface'] ?? '';
        $studentApplications->primary_computer_recording_equipment = $input['primary_computer_recording_equipment'] ?? '';

        $formPartNo = self::checkFormPart($studentApplicationsId, self::FORM_PART_SIXTH);
        if ($formPartNo) {
            $studentApplications->form_part = self::FORM_PART_SIXTH;
        }

        $returnValue = 0;
        if ($studentApplications->save()) {
            $returnValue = $studentApplications->id;
        }
        return $returnValue;
    }

    /**
     * @param type $userId
     * @param type $studentApplicationsId
     * @param type $input
     * 
     * @return type
     */
    public static function setupCourseDetailsForm_6($studentApplicationsId, $input) {
        if ($studentApplicationsId) {
            return self::updateCourseDetailsForm_6($studentApplicationsId, $input);
        }
    }

    /**
     * @param int $studentApplicationsId
     * @param array $input
     * 
     * @return int
     */
    public static function updateCourseDetailsForm_7($studentApplicationsId, array $input) {

        $studentApplications = self::getStudentApplicationsById($studentApplicationsId);
        $studentApplications->highest_qualification = $input['highest_qualification'] ?? '';
        $studentApplications->country_hq = $input['country'] ?? '';
        $studentApplications->institution_hq = $input['institution'] ?? '';
        $studentApplications->subject_hq = $input['subject'] ?? '';
        $studentApplications->grade_hq = $input['grade'] ?? '';
        $studentApplications->examining_body_hq = $input['examining_body'] ?? '';
        $studentApplications->setting_hq = $input['setting'] ?? '';
        $studentApplications->year_hq = $input['year'] ?? '';

        $formPartNo = self::checkFormPart($studentApplicationsId, self::FORM_PART_SEVENTH);
        if ($formPartNo) {
            $studentApplications->form_part = self::FORM_PART_SEVENTH;
        }

        $returnValue = 0;
        if ($studentApplications->save()) {
            $returnValue = $studentApplications->id;
        }
        return $returnValue;
    }

    /**
     * Method gets Mode Of Study
     * @param string $degreeType
     * @return array
     */
    public static function getModeOfStudy(string $degreeType = 'MA') {

        if ($degreeType == 'MA') {
            $modeOfStudyArray = [
                'Full Time' => 'Full Time',
                'Part Time: 2 Years' => 'Part Time: 2 Years',
                'Part Time: 3 Years' => 'Part Time: 3 Years',
                'Part Time: 4 Years' => 'Part Time: 4 Years'];
        } else {
            // MFA 
            $modeOfStudyArray = [
                'Full Time: 2 Years' => 'Full Time: 2 Years',
                'Part Time: 3 Years' => 'Part Time: 3 Years',
                'Part Time: 4 Years' => 'Part Time: 4 Years'];
        }

        return $modeOfStudyArray;
    }

    /**
     * Method gets Qualification Types
     * @param string $degreeType
     * @return array
     */
    public static function getQualificationTypes() {


        $qualificationTypes = [
            "Full Master's Degree" => "Full Master's Degree",
            'Postgraduate Diploma' => 'Postgraduate Diploma',
            'Postgraduate Certificate' => 'Postgraduate Certificate',
        ];
        return $qualificationTypes;
    }

    /**
     * Method gets Require Adjustments
     * @return array
     */
    public static function getRequireAdjustments() {

        return $requireAdjustmentsArray = [
            false => 'No',
            true => 'Yes'
        ];
    }

    /**
     * Method gets Require Adjustments Name
     * @return array
     */
    public static function getRequireAdjustmentsName($value) {

        switch ($value) {
            case 0:
                return "No";
            case 1:
                return "Yes";
        }
    }

    /**
     * Method gets Type Work InIndustry
     * @return array
     */
    public static function getTypeWorkInIndustry() {

        return $typeWorkInIndustryArray = [
            '0' => 'Freelance',
            '1' => 'Self-Employed'
        ];
    }

    /**
     * Method gets Title
     * @return array
     */
    public static function getTitle() {

        return $requireTitleArray = [
            '0' => 'Mr',
            '1' => 'Mrs',
            '2' => 'Miss',
            '3' => 'Ms',
            '4' => 'Dr',
            '5' => 'Rev',
            '6' => 'Other',
        ];
    }

    /**
     * Method gets Sitting
     * @return array
     */
    public static function getSitting() {

        return $setting = [
            'S' => 'Summer',
            'W' => 'Winter',
        ];
    }
    /**
     * Method gets Sitting Name
     * @return array
     */
    public static function getSittingName($value) {

        switch ($value) {
            case 'S':
                return "Summer";
            case 'W':
                return "Winter";
        }
    }

    /**
     * Methods check if country is UK or EU 
     * @param type $studentApplicationsId
     * @return string
     */
    public static function checkCountrie($studentApplicationsId) {

        $studentCountry = StudentApplications::getStudentApplicationsById($studentApplicationsId);

        if (self::checkCodeIsUK($studentCountry->current_country)) {
            return 'UK';
        } else {
            if (self::checkCountrieIsEU($studentCountry->current_country)) {
                return 'EU';
            } else {
                return 'World';
            }
        }
    }

    /**
     * Method check if country is in EU
     * @param type $studentApplicationsId
     * @param type $studentCountry
     * @return int
     */
    public static function checkCountrieIsEU($studentCountry) {

        if (in_array($studentCountry, self::getEUCountries())) {
            return 1;
        }
        return 0;
    }

    /**
     * Method gets EUCountries
     * @return type
     */
    public static function getEUCountries() {

        return $setting = [
            'Austria' => 'AT',
            'Belgium' => 'BE',
            'Cyprus' => 'CY',
            'Czech Republic' => 'CZ',
            'Denmark' => 'DK',
            'Estonia' => 'EE',
            'Finland' => 'FI',
            'Greece' => 'GR',
            'Spain' => 'ES',
            'Ireland' => 'IE',
            'Lithuania' => 'LT',
            'Luxemburg' => 'LU',
            'Latvia' => 'LV',
            'Malta' => 'MT',
            'Netherlands' => 'NL',
            'Germany' => 'DE',
            'Poland' => 'PL',
            'Portugal' => 'PT',
            'Slovakia' => 'SK',
            'Slovenia' => 'SI',
            'Hungary' => 'HU',
            'Italy' => 'IT'
        ];
    }

    /**
     * Methods check of code is UK
     * @param type $studentApplicationsId
     * @param type $studentCountry
     * @return int
     */
    public static function checkCodeIsUK($studentCountry) {

        if (in_array($studentCountry, self::getCodeUK())) {
            return 1;
        }
        return 0;
    }

    /**
     * Method gets codeUK
     * @return type
     */
    public static function getCodeUK() {

        return $setting = [
            'England' => 'XF',
            'Wales' => 'XI',
            'United Kingdom, not otherwise specified' => 'XK',
            'Scotland' => 'XH',
            'Northern Ireland' => 'XG',
            'Channel Islands not otherwise specified' => 'XL'
        ];
    }

    /**
     * Method gets codes highest qualification - Not University Level
     * @return array
     */
    public static function getHighestQualificationCode() {


        return $setting = [
            'Diploma at level 3' => 'P41',
            'Certificate at level 3' => 'P42',
            'Award at level 3' => 'P46',
            'AQA Baccalaureate (Bacc)' => 'P47',
            'A/AS level' => 'P50',
            '14-19 Advanced Diploma (level 3)' => 'P51',
            'Scottish Baccalaureate' => 'P53',
            'Scottish Highers/Advanced Highers' => 'P54',
            'International Baccalaureate (IB) Diploma' => 'P62',
            'International Baccalaureate (IB) Certificate' => 'P63',
            'Cambridge Pre-U Diploma' => 'P64',
            'Cambridge Pre-U Certificate' => 'P65',
            'Welsh Baccalaureate Advanced Diploma (level 3)' => 'P68',
            'Level 3 qualifications of which all are subject to UCAS Tariff' => 'P93',
            'Level 3 qualifications of which some are subject to UCAS Tariff' => 'P94',
            'Higher education (HE) access course, Quality Assurance Agency (QAA) recognised' => 'X00',
            'Higher education (HE) access course, not Quality Assurance Agency (QAA) recognised' => 'X01',
        ];
    }

    /**
     * Methods checks if $studentCountry is in HighestQualification array
     * @param type $studentApplicationsId
     * @param type $studentCountry
     * @return int
     */
    public static function checkHighestQualificationCode($highestQualificationCode) {

        if (in_array($highestQualificationCode, self::getHighestQualificationCode())) {
            return 1;
        }
        return 0;
    }

    /**
     * Method gets codes UK / channel islands / isle of Man
     * @return type
     */
    public static function getCountryCode() {

        return $setting = [
            'England' => 'XF',
            'Northern Ireland' => 'XG',
            'United Kingdom, not otherwise specified' => 'XK',
            'Scotland' => 'XH',
            'Channel Islands not otherwise specified' => 'XL',
            'Wales' => 'XI',
            'Guernsey' => 'GG',
            'Jersey' => 'JE',
            'Isle of Man' => 'IM',
        ];
    }

    /**
     * Methods checks  if $studentPreviousCountry  is in array codes ( UK or channel islands or isle of Man)
     * @param type $studentPreviousCountry
     * @return int
     */
    public static function checkCountryCode($studentPreviousCountry) {

        if (in_array($studentPreviousCountry, self::getCountryCode())) {
            return 1;
        }
        return 0;
    }

    /**
     * Methods gets LabelName 
     * @param type $field, $table, $hesa_code
     * @return string
     */
    public static function getNameofLabel($field, $table, $hesa_code) {

        $nameofLabel = DB::table('student_applications')->
                        where($field, '=', $hesa_code)->
                        leftJoin($table, $table . '.hesa_code', '=', 'student_applications.' . $field)->first();

        return $nameofLabel->hesa_label;
    }

    /**
     * Methods gets NameofDisables
     * @param type $table, $field
     * @return array
     */
    public static function getNameofDisables($table, $field) {
        $disableIds = explode(',', $field);
        return DB::table($table)->whereIn('hesa_code', $disableIds)->get();
    }

}
