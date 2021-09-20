<?php

namespace App\Models\Hesa\StudentAlternative;

//use Illuminate\Database\Eloquent\Model;
use App\Models\Hesa\StudentAlternative\Record\Fields;
use App\Models\Hesa\Collections;
use App\Models\Hesa\StudentAlternative;
use App\Models\StudentApplications;
use App\Models\Hesa;
use Illuminate\Support\Facades\DB;
use App\Models\StudentApplicationsQualifications;

class Record
{
    public $fields;
    public $collection;

    public function __construct()
    {
        // :Todo
        // hardcoded for now (Mar 2021)
        // this will need to respond to specific periods

        $this->loadCollection (4);
    }

    public function loadCollection($id)
    {
        $this->collection = Collections::findOrFail($id);
        if ($this->collection->type <> 'studentalternative') {
            die('Error: not a student alternative collection id...');
        }
        $this->fields = Fields::where('hesa_collection_id',$id)->orderBy('code','asc')->get();

        return $id;
    }

    public function generateXml()
    {
        // innit error session
        //error flag
        session()->put('hesa_error', false);
        //error container
        session()->put('hesa_errors', array());

        $xml = '<Provider>';
            $fields = $this->fields;

            // Provider
            // Provider > Course
            // Provider > Course > Course Subject
            // Provider > Course > Delivery organisation and location

            // Provider > Student
            // Provider > Student > Entry profile
            // Provider > Student > Entry profile > Qualifications on entry

            // Provider > Student > Instance
            // Provider > Student > Instance > Financial support
            // Provider > Student > Instance > Instance Period
            // Provider > Student > Instance > Instance Period > Qualifications awarded

            //  3x constant fields
            $institution = $fields->where('parent', 'provider');
            // $institution->where('code', 'RECID');
            // $institution->where('code', 'UKPRN');
            foreach ($institution AS $field) {
                $xml .= StudentAlternative::processField($field);
            }

            $course_fields = $fields->where('parent', 'course');
            $delivery_fields = $fields->where('parent', 'delivery organisation and location');
            $subject_fields = $fields->where('parent', 'course subject');

            // fetch all courses
            $courses = $this->fetchAllCourses();

//DISABLED COURSE!
//$xml .= '<Course> !!!!! CURRENTLY DISABLED !!!!!</Course>';
//$courses = [];

            // for each course
            foreach ($courses as $course) {
                // insert error header for this student
                $msg = 'Hesa Alternative Course: ';
                $msg .= ' <strong>Course Title: ' . $course->course_title . '</strong> ';
                $msg .= '[ ' . $course->course_group_id . ' ]';
                session()->push('hesa_errors', $msg);

                $xml .= '<Course>';

                // course data
                foreach ($course_fields as $field) {
                    $xml .= StudentAlternative::processField($field, $course);
                }

                // lookup Delivery organisation and location(s)
                // 2 x constant fields
                $xml .= '<DeliveryOrganisationAndLocation>';
                    // Delivery organisation and location data ( >=0)
                    foreach ($delivery_fields as $field) {
                        $xml .= StudentAlternative::processField($field);
                    }
                $xml .= '</DeliveryOrganisationAndLocation>';


                // fetch subjects
                $subjects = $this->fetchSubjects($course->course_group_id);

                foreach ($subjects as $subject) {
                    // lookup subject(s)
                    $xml .= '<CourseSubject>';

                    foreach ($subject_fields as $field) {
                        // course subject data (0 <> 5)
                        $xml .= StudentAlternative::processField($field, $subject);
                    }

                    $xml .= '</CourseSubject>';
                }


                $xml .= '</Course>';
            }

            $student_fields = $fields->where('parent', 'student');
            $student_equality_fields = $fields->where('parent', 'student equality');
            $student_entry_profile_fields = $fields->where('parent', 'entry profile');
            $student_qualifications_on_entry_fields = $fields->where('parent', 'qualifications on entry');
            $student_instance_fields = $fields->where('parent', 'instance');
            $student_financial_support_fields = $fields->where('parent', 'financial support');
            $student_instance_period_fields = $fields->where('parent', 'instance period');

            $students = $this->fetchStudents();

            // for each student
            foreach ($students AS $student) {
                // insert error header for this staff member
                $msg = '<br>Hesa Alternative Student: ';
                $msg .= ' <strong>' . $student->user_first . ' ';
                $msg .= ' ' . $student->user_last . '</strong> ';
                $msg .= '[ username: ' . $student->user_username . ' ]';
                $msg .= '[ user_id: ' . $student->user_id . ' ]';
                session()->push('hesa_errors', $msg);

                $user_id = $student->user_id;

                // fetch most recent application form, if any, to get the most recent full name/dob.
                $most_recent_student_application = StudentApplications::where('user_id', $user_id)->orderBy('id', 'DESC')->first();
                // Fetch Student Events for current section
                $student_events = $this->fetchStudentEvents($user_id);
                // Fetch Student Qualifications
                if ($most_recent_student_application != null) {
                    $applications_id = $most_recent_student_application->id;
                } else {
                    $applications_id = 0; // i.e. don't find a match.
                }

                $student_qualifications = $this->fetchQualifications($applications_id);

                $xml .= '<Student>';

                    // loop through $student_fields
                    foreach ($student_fields AS $field) {
                        $xml .= StudentAlternative::processField($field, $student, false,  $most_recent_student_application);
                    }

                    // >> fetch instances for student
                    $instances = $this->fetchInstances($user_id);

                    // >> for each instance:
                    foreach ($instances AS $instance) {
                        $msg .= '<br><span style="margin-left: 10px;"> Course_name: ' . $instance->course_name . ' ';
                        $msg .= '[ ' . $instance->course_id . ' ]';
                        $msg .= ' Course_schedule_name: ' . $instance->course_schedule_name . ' ';
                        $msg .= '[ ' . $instance->course_schedule_id . ' ]';
                        $msg .= ' user_course_lookup_id: ' . $instance->user_course_lookup_id . '</span>';
                        session()->push('hesa_errors', $msg);

                        // >> fetch application form
                        $application_form = $this->fetchApplicationForm($instance->user_course_lookup_id);

                        if ($application_form->isEmpty()) {
                            $msg = 'ERROR: <strong>APPLICATION FORM MISSING!...[where user_course_lookup_id = ' . $instance->user_course_lookup_id . ']</strong>';
                            session()->push('hesa_errors', $msg);
                            session()->put('hesa_error', true);
                        }

                        // include entry profile if starting during this collection
                        $c_start_dt = $this->collection->period_start;
                        $collection_start = strtotime($c_start_dt);
                        $degree_start = $instance->degree_start;

                        if ($degree_start > $collection_start) {

                            $xml .= '<EntryProfile>';


                            //set current UCL to student object
                            $student->user_course_lookup_id = $instance->user_course_lookup_id;

                            // loop through
                            foreach ($student_entry_profile_fields as $field) {
                                $xml .= StudentAlternative::processField($field, $student, false,  $application_form);
                            }

                            // optional
                            foreach ($student_qualifications as $student_qualification) {
                                // entry profile > qualifications on entry
                                $xml .= '<QualificationsOnEntry>';
                                foreach ($student_qualifications_on_entry_fields as $field) {
                                    $xml .= StudentAlternative::processField($field, $student_qualification, false,  $application_form);
                                }
                                $xml .= '</QualificationsOnEntry>';
                            }

                            $xml .= '</EntryProfile>';

                        }

                        // Instance
                        $xml .= '<Instance>';
                        // Provider > Student > Instance
                        // Provider > Student > Instance > Financial support
                        // Provider > Student > Instance > Instance Period
                        // Provider > Student > Instance > Instance Period > Qualifications awarded

                        // core fields
                        foreach ($student_instance_fields AS $field) {
                            $xml .= StudentAlternative::processField($field, $instance, $student_events,  $application_form);
                        }

                        // fetch financials for this course lookup
                        $student_events_financials = $student_events
                             ->where('type_id', '=', '130')
                             ->where('user_course_lookup_id', '=', $instance->user_course_lookup_id);
                        foreach ($student_events_financials AS $finance) {
                            $xml .= '<FinancialSupport>';
                                $finance_data = $finance->event_value;
                                foreach ($student_financial_support_fields AS $field) {
                                    $xml .= StudentAlternative::processField($field, $instance, $finance_data,  $application_form);
                                }
                            $xml .= '</FinancialSupport>';
                        }

                        $student_events_instance = $student_events
                            ->where('user_course_lookup_id', '=', $instance->user_course_lookup_id);

                        // fetch dormant periods for this course lookup
                        $student_events_dormant = $student_events
                             ->where('type_id', '=', '102')
                             ->where('user_course_lookup_id', '=', $instance->user_course_lookup_id);

                        $xml .= $this->fetchInstancePeriods($student, $instance, $student_events_dormant, $student_instance_period_fields, $student_events_instance);

                        $xml .= '</Instance>';
                    }

                    $xml .= '<StudentEquality>';
                    // loop through $student_equality_fields
                    foreach ($student_equality_fields AS $field) {
                        $xml .= StudentAlternative::processField($field, $student, false,  $most_recent_student_application );
                    }
                    $xml .= '</StudentEquality>';

                $xml .= '</Student>';
            }

        $xml .= '</Provider>';

        return Hesa::renderXML($this->collection, $xml, 'file');

//        Todo: change to file
//        return Hesa::renderXML($this->collection, $xml, 'file');

    }

    public function fetchAllCourses()
    {
        //\DB::connection('mysql_vle')->enableQueryLog();

        // NOTE:
        // AWARDBOD = array, but we only have one value

        // fetch all relevant course schedules for current reporting period
        $course_schedules = $this->fetchCourseSchedules();

        return DB::connection('mysql_vle')
            ->table('course_group AS cg')
            ->leftjoin('course AS c', 'c.course_group_id', '=', 'cg.course_group_id')
            ->leftjoin('course_schedule AS cs', 'cs.course_id', '=', 'c.course_id')
            ->select('cg.*')
            ->where('cg.deleted', '=', '0')
            ->where('cg.include_in_hesa_report', '=', '1')
            ->where('c.deleted', '=', '0')
            ->where('cs.deleted', '=', '0')
            ->where('cs.omit_from_hesa_report', '=', '0')
            ->where('c.omit_from_hesa_report', '=', '0')
            ->whereIn('cs.course_schedule_id', $course_schedules)
            ->distinct()
            ->get();
            //echo "<pre>";
            //var_dump(\DB::connection('mysql_vle')->getQueryLog());
            //echo "</pre>";
    }

    public function fetchSubjects($course_group_id)
    {
        return DB::connection('mysql_vle')
            ->table('course_group_subject AS cgs')
            ->where('cgs.deleted', '=', '0')
            ->where('cgs.course_group_id', '=', $course_group_id)
            ->get();
    }

    // helper
    public function fetchCourseSchedules()
    {
//        \DB::connection('mysql_vle')->enableQueryLog();

        $start = $this->collection->period_start;
        $end = $this->collection->period_end;
        // convert to epoch
        $start = strtotime($start);
        $end = strtotime($end);

        // fetch all relevant course schedules for current reporting period
        $course_schedules = DB::connection('mysql_vle')
            ->table('course_group AS cg')
            ->leftjoin('course AS c', 'c.course_group_id', '=', 'cg.course_group_id')
            ->leftjoin('course_schedule AS cs', 'cs.course_id', '=', 'c.course_id')
            ->select(
                'cs.course_schedule_id',
            )
            ->where('cg.deleted', '=', '0')
            ->where('cg.include_in_hesa_report', '=', '1')
            ->where('c.deleted', '=', '0')
            ->where('c.course_type_id', '=', '2')
            ->where('c.omit_from_hesa_report', '=', '0')
            ->where('cs.deleted', '=', '0')
            ->where('cs.omit_from_hesa_report', '=', '0')
            ->where(function ($query) use ($start, $end){
                return $query
                    // STARTED - within period
                    ->Where(
                        function ($query) use ($start, $end){
                            return $query->where('degree_start', '>=', $start)
                                ->where('degree_start', '<=', $end);
                        })

                    // SPANNED - across period - started before and ended after
                    ->orWhere(
                        function ($query) use ($start, $end){
                            return $query->where('degree_start', '<=', $start)
                                        ->where('degree_end', '>=', $end);
                        })
                    // ENDED - within period
                    ->orWhere(
                        function ($query) use ($start, $end){
                            return $query->where('degree_end', '>=', $start)
                                ->where('degree_end', '<=', $end);
                        })
                    ;
            })
            ->distinct()
            ->get()->toArray();
//            echo "<pre>";
//            var_dump(\DB::connection('mysql_vle')->getQueryLog());
//            echo "</pre>";

        $course_schedules = array_column($course_schedules,'course_schedule_id');

        return $course_schedules;
    }

    public function fetchStudents()
    {
        // \DB::connection('mysql_vle')->enableQueryLog();

        // IMPORTANT:
        Hesa::syncStudentApplicationsToVle();

        // living in uk only = 228,229,230,250

        // fetch all relevant course schedules for current reporting period
        $course_schedules = $this->fetchCourseSchedules();

        // $course_schedules = implode(',',$course_schedules);

        // fetch all students on these schedules
        $students = DB::connection('mysql_vle')
            ->table('user AS u')
            ->leftjoin('student_record AS sr', 'sr.user_id', '=', 'u.user_id')
            ->leftjoin('user_course_lookup AS ucl', 'ucl.user_id', '=', 'u.user_id')
            ->leftjoin('tmp_student_applications AS sa', 'sa.user_course_lookup_id', '=', 'ucl.user_course_lookup_id')
//            ->leftjoin('country', 'country.country_id', '=', 'sr.hesa_nationality')
//            ->leftjoin('country AS country2', 'country2.country_id', '=', 'sr.student_country_id')
            ->select(
                'u.user_id',
                'u.user_username',
                'u.user_first',
                'u.user_last',
                'sr.student_dob',
                'sr.student_postcode',
//                'sr.hesa_student_support_number',
//                'sr.hesa_ucas_personal_identifier',
                'sr.hesa_unique_learner_number',
                'sr.hesa_unique_student_identifier',
//                'sr.hesa_disability',
//                'sr.hesa_disall',
//                'sr.hesa_ethnicity',
//                'sr.hesa_gender_identity',
//                'sr.hesa_religion_or_belief',
//                'sr.hesa_sex_identifier',
//                'sr.hesa_sexual_orientation',
//                'sr.hesa_domicile',
//                'sr.hesa_highest_qualification_on_entry',
//                'sr.hesa_ucas_application_scheme_code',
//                'sr.hesa_mode',
//                'sr.hesa_mstufee',
//                'country.iso_two_hesa',
//                'country2.iso_two_hesa AS hesa_domicile_backup',
                // 'ucl.user_course_lookup_id',
            )
  			->where('u.deleted', '=', '0')
          	->where('u.omit_from_hesa_report', '=', '0')
  			->where('ucl.deleted', '=', '0')
  			->whereIn('ucl.course_schedule_id', $course_schedules)
    		->where('sr.deleted', '=', '0')
//    		->whereIn('sr.student_country_id', array(228,229,230,250))
//    		->whereIn('sa.student_country_id', array(228,229,230,250))
    		->where('sa.iso_two_hesa', '=', 'GB')
            ->groupBy('u.user_id')
          	->get();
             // echo "<pre>";
             // var_dump(\DB::connection('mysql_vle')->getQueryLog());
             // echo "</pre>";

        return $students;
    }

    public function fetchQualifications($application_id)
    {
        return StudentApplicationsQualifications::where('student_applications_id', $application_id)->get()->toArray();
    }

    public function fetchInstances($student_id)
    {
         // \DB::connection('mysql_vle')->enableQueryLog();

        $course_schedules = $this->fetchCourseSchedules();

        // lookup user_course_lookup(s) for this student in this period
        // fetch required instance data

        // living in uk only = 228,229,230,250

        // fetch all relevant course schedules for current reporting period for this student
        $instances = DB::connection('mysql_vle')
            ->table('user_course_lookup AS ucl')
            ->leftjoin('course_schedule AS cs', 'cs.course_schedule_id', '=', 'ucl.course_schedule_id')
            ->leftjoin('course_schedule_type AS cst', 'cst.course_schedule_type_id', '=', 'cs.course_schedule_type_id')
            ->leftjoin('course AS c', 'c.course_id', '=', 'ucl.course_id')
            ->leftjoin('user AS u', 'u.user_id', '=', 'ucl.user_id')
            ->leftjoin('student_record AS sr', 'sr.user_id', '=', 'u.user_id')
            ->select(
                'c.course_id',
                'c.course_name',
                'cs.course_schedule_id',
                'cs.degree_start',
                'cs.degree_end',
                'cs.course_schedule_name',
                'ucl.user_course_lookup_id',
                'c.course_group_id',
                'cst.schedule_type_name AS schedule_fte',
            )
            ->where('ucl.user_id', '=', $student_id)
            ->where('ucl.deleted', '=', '0')
            ->whereIn('cs.course_schedule_id', $course_schedules)
            ->where('cs.deleted', '=', '0')
            ->where('cs.omit_from_hesa_report', '=', '0')
            ->where('u.deleted', '=', '0')
            ->where('sr.deleted', '=', '0')
    		->whereIn('sr.student_country_id', array(228,229,230,250))
            ->get();
             // echo "<pre>";
             // var_dump(\DB::connection('mysql_vle')->getQueryLog());
             // echo "</pre>";
             // die();

        return $instances;
    }

    public function fetchInstancePeriods($student, $instance, $student_events_dormant, $fields, $student_events_instance)
    {
        // fetch timeline array
        $timeline = $this->fetchTimeline($instance, $student_events_dormant, $student_events_instance);

        $_xml = '';

        $collection = false;
        $degree = false;
        $dormant = false;

        $last_event = '';

        // innit
        // default to current schedule FTE
        // if schedule changes are set in user_events, then they will override this value accordingly

        $current_fte = $instance->schedule_fte;
        $new_fte = false;
        $start = '';
        $end = '';
        $period_has_dormant = false;
        $dormant_start = '';
        $dormant_end = '';
        $YEARPRG = 0;
        $YEARSTU = 1;

        // loop through timeline and create periods as required
        foreach ($timeline AS $event) {
            $current_event = $event['type'];

            if ($current_event == 'anniversary') {
                // just bump up the year of study values
                $YEARPRG++;
                $YEARSTU++;
            }
            if ($current_event == 'schedule_start') {
                $current_fte = $event['value'];
            }

            // only do something when there's a change - except for anniversaries (which should be evey time)
            if ($last_event <> $current_event) {
                if ($current_event == 'degree_start') {
                    $degree = true;
                }
                if ($current_event == 'degree_end') {
                    $degree = false;
                }
                if ($current_event == 'dormant_start') {
                    $period_has_dormant = true;
                }
                // if dormant period ended & collection period not started then it was historical
                if (($current_event == 'dormant_end') && (!$collection)) {
                    $period_has_dormant = false;
                }

                // if we have already started...
                if ($collection) {
                    //innit
                    $buildPeriod = false;

                    // degree starts during period
                    if ($current_event == 'degree_start') {
                        $start = $event['start_dt'];
                    }
                    // degree ends during period
                    if ($current_event == 'degree_end') {
                        $end = $event['end_dt'];
                        $buildPeriod = true;
                    }

                    //course schedule change
                    if ($current_event == 'schedule_change') {
                        $new_fte = $event['value'];
                        $end = $event['start_dt'];
                        $buildPeriod = true;
                    }

                    // dormant period starts within collection, (we assume this happens AFTER degree begins)
                    if ($current_event == 'dormant_start') {
                        $period_has_dormant = true;
                        $dormant_start = $event['start_dt'];
                    }
                    // dormant period ends
                    if (($period_has_dormant) && ($current_event == 'dormant_end')) {
                        $end = $event['end_dt'];
                        $dormant_end = $event['end_dt'];
                        $buildPeriod = true;
                    }
                    // if anniversary, need to start a new period for that
                    if ($current_event == 'anniversary') {
                        $end = $event['start_dt'];
                        $buildPeriod = true;
                    }
                    //if collection ends, we need to end period
                    if ($current_event == 'collection_end') {
                        $end = $event['end_dt'];
                        $buildPeriod = true;
                    }
                    /////////////////////////////////////////////////////////
                    /////////////////////////////////////////////////////////
                    ///// BUILD PERIOD //////////////////////////////////////
                    /////////////////////////////////////////////////////////
                    /////////////////////////////////////////////////////////
                    if ($buildPeriod) {
                        // innit
                        $period_array = array();

                        //calculate dormant length if dormant....
                        if ($period_has_dormant) {
                            $period_array['dormant_start'] = $dormant_start;
                            $period_array['dormant_end'] = $dormant_end;
                            $period_array['period_has_dormant'] = true;
                        } else {
                            $period_array['period_has_dormant'] = false;
                        }

                        //evaluate FTE
                        $period_array['fte'] = $current_fte;

                        // other data to pass
                        $period_array['start'] = $start;
                        $period_array['end'] = $end;
                        $period_array['YEARPRG'] = $YEARPRG;
                        $period_array['YEARSTU'] = $YEARSTU;

                        $build_instance = (array) $instance;
                        $build_student = (array) $student;
                        //build period XML
                            $period_data = (object) array_merge((array) $period_array, (array) $build_instance, (array) $build_student);

                            $_xml .= $this->buildPeriod($fields, $period_data, $student_events_instance);

                        //start new period
                            if ($period_has_dormant) {
                                // start at end of dormant period
                                $start = $dormant_end;
                            } else {
                                // start at this point
                                // e.g. degree start, anniversary,
                                $start = $event['start_dt'];
                            }

                        //reset values
                            $dormant_start = '';
                            $dormant_end = '';
                            $period_has_dormant = false;
                            $dormant = false;
                            $end = '';
                            //start new period with new FTE if there is one
                            if ($new_fte) {
                                $current_fte = $new_fte;
                            }

                        //update last event
                            $last_event = $current_event;

                        if ($current_event == 'collection_end') {
                            //all done here
                            break;
                        } else {
                            //jump to next event
                            continue;
                        }
                    }

                } elseif ($current_event == 'collection_start') {
                    //
                    // we are starting
                    //
                    $collection = true;

                    //if degree started before collection, then start period from this point
                    if ($degree) {
                        $start = $event['start_dt'];
                    }
                    // starting period in dormant
                    if ($period_has_dormant) {
                        $dormant_start = $event['start_dt'];
                    }
                }

                //update last event
                $last_event = $current_event;
            } else {
                // ignore new event with same name - should this ever happen?
                continue;
            }
        }

        return $_xml;
    }

    // helper
    public static function fetchApplicationForm($user_course_lookup_id)
    {
        // fetch student_application
        $student_application = StudentApplications::where('user_course_lookup_id', $user_course_lookup_id)->get();

         return $student_application;
    }
    public static function fetchApplicationFormEmployment($student_applications_id)
    {
        // fetch student_applications_employment

        // return $instances;
    }
    public static function fetchApplicationFormQualifications($student_applications_id)
    {
        // fetch student_applications_qualifications

        // return $instances;
    }

    // helper
    public function fetchTimeline($instance, $student_events_dormant, $student_events_instance)
    {
        $timeline = array();

        // collection dates
        $c_start_dt = $this->collection->period_start;
        $c_start_ts = strtotime($c_start_dt);
        $c_end_dt = $this->collection->period_end;
        $c_end_ts = strtotime($c_end_dt);

        // course schedule dates
        $d_start_ts = $instance->degree_start;
        $d_start_dt = date("Y-m-d", $d_start_ts);
        $d_end_ts = $instance->degree_end;
        $d_end_dt = date("Y-m-d", $d_end_ts);

        foreach ($student_events_dormant AS $dormant) {
            $dormant_start_ts = $dormant->event_start;
            $dormant_start_dt = date("Y-m-d", $dormant_start_ts);
            $dormant_end_ts = $dormant->event_end;

            if ($dormant_end_ts > 0) {
                $dormant_end_dt = date("Y-m-d", $dormant_end_ts);
            } else {
                $dormant_end_dt = '';
            }
            $timeline[$dormant_start_ts] = array("start_dt"=>$dormant_start_dt, "end_dt"=> $dormant_end_dt, "type"=>"dormant_start");
            if (strlen($dormant_end_ts) > 0) {
                // no end on dormant, so still active
                $timeline[$dormant_end_ts] = array("start_dt"=>$dormant_start_dt, "end_dt"=> $dormant_end_dt, "type"=>"dormant_end");
            }
        }

        // handle course schedule changes, if any
        $student_events_schedules = $student_events_instance
            ->where('type_id', '>=', '140')
            ->where('type_id', '<=', '141')
            ->where('user_course_lookup_id', '=', $instance->user_course_lookup_id);

        foreach ($student_events_schedules AS $schedule) {
            $date_ts = $schedule->event_start;
            $date_dt = date("Y-m-d", $date_ts);
            if ($schedule->type_id == "140") {
                $timeline[$date_ts-1000] = array("start_dt" => $date_dt, "end_dt" => "", "type" => "schedule_start", "value" => $schedule->schedule_fte);
            }
            if ($schedule->type_id == "141") {
                $timeline[$date_ts] = array("start_dt" => $date_dt, "end_dt" => "", "type" => "schedule_change", "value" => $schedule->schedule_fte);
            }
        }

        // need to add any anniversaries to track year of study
        //quick dirty method - add 10 years' worth of anniversaries to any course - should be plenty and it's negligible in the scheme of things
        for($i = 1; $i<=10; $i++) {
            $anniversary_time = \Carbon\Carbon::createFromFormat('Y-m-d', $d_start_dt)->addYears($i);
            $timeline[$anniversary_time->timestamp] = array("start_dt"=>$anniversary_time->format('Y-m-d'), "type"=>"anniversary", "value"=>$i);
        }

        // build timeline array
        $timeline[$c_start_ts] = array("start_dt"=>$c_start_dt, "end_dt"=> $c_end_dt, "type"=>"collection_start");
        $timeline[$c_end_ts] = array("start_dt"=>$c_start_dt, "end_dt"=> $c_end_dt, "type"=>"collection_end");
        $timeline[$d_start_ts] = array("start_dt"=>$d_start_dt, "end_dt"=> $d_end_dt, "type"=>"degree_start");
        $timeline[$d_end_ts] = array("start_dt"=>$d_start_dt, "end_dt"=> $d_end_dt, "type"=>"degree_end");

        ksort($timeline);

        return $timeline;
    }

    // helper
    public function buildPeriod($fields, $period_data, $student_events)
    {

        $period_data = (object) $period_data;

        $_xml = '<InstancePeriod>';
        foreach ($fields AS $field) {
            // instance period
            $_xml .= StudentAlternative::processField($field, $period_data, $student_events);
        }

        //QualificationsAwarded within period
        $start = strtotime($period_data->start);
        $end = strtotime($period_data->end);

        // STUDENT_COURSE_AWARD = 190
        $qualifications = $student_events
             ->where('type_id', '=', '190')
             ->where('event_start', '>=', $start)
             ->where('event_start', '<=', $end);
        $counter = 0;
        foreach ($qualifications AS $qualification) {
            $counter++;
            if ($counter > 2) {
                break;
            }
            $_xml .= '<QualificationsAwarded>';
            $_xml .= '<CLASS>' . $qualification->event_value . '</CLASS>';
            $_xml .= '<QUAL>M00</QUAL>';
            $_xml .= '</QualificationsAwarded>';
        }

        $_xml .= '</InstancePeriod>';
        return $_xml;
    }

    public function fetchStudentEvents($user_id)
    {
        // \DB::connection('mysql_vle')->enableQueryLog();

        $start = $this->collection->period_start;
        $end = $this->collection->period_end;
        // convert to epoch
        $start = strtotime($start);
        $end = strtotime($end);

        $student_events = DB::connection('mysql_vle')
        ->table('user_event AS ue')
        ->leftjoin('course_schedule AS cs', 'cs.course_schedule_id', '=', 'ue.event_value')
        ->leftjoin('course_schedule_type AS cst', 'cst.course_schedule_type_id', '=', 'cs.course_schedule_type_id')
        ->select('ue.*', 'cst.schedule_type_name AS schedule_fte')
        ->where('ue.deleted', '=', '0')
            ->where('user_id', '=', $user_id)
        ->where(function ($query) use ($start, $end){
            return $query
                // STARTED - within period
                ->Where(
                    function ($query) use ($start, $end){
                        return $query->where('event_start', '>=', $start)
                            ->where('event_start', '<=', $end);
                    })

                // SPANNED - across period - started before and ended after
                ->orWhere(
                    function ($query) use ($start, $end){
                        return $query->where('event_start', '<=', $start)
                                    ->where('event_end', '>=', $end);
                    })
                // ENDED - within period
                ->orWhere(
                    function ($query) use ($start, $end){
                        return $query->where('event_end', '>=', $start)
                            ->where('event_end', '<=', $end);
                    })
                ;
        })
        ->orderBy('event_start')
        ->get();

        // echo "<pre>";
        // var_dump(\DB::connection('mysql_vle')->getQueryLog());
        // echo "</pre>";
        // die();

        return $student_events;
    }
}
