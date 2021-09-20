<?php
namespace App\Models\Hesa;

use App\Models\Hesa\StudentAlternative\Validation;

class StudentAlternative
{
    public static function processField($field, $data = null, $student_events = null, $student_application = null)
    {
        $return = '';
        $value = '';

        switch ($field->reporter) {
            case 'constant':
                $value = StudentAlternative::fetchConstant($field);
                break;
            case 'calculated':
                $value = StudentAlternative::fetchCalculated($field, $data, $student_events, $student_application);
                break;
//            case 'self':
            case 'admin-course':
                $value = StudentAlternative::fetchAdminCourse($field, $data);
                break;
            case 'admin-student-event':
                $value = StudentAlternative::fetchAdminStudentEvent($field, $data, $student_events);
                break;
            case 'self-profile':
            case 'admin-student-profile':
                $value = StudentAlternative::fetchSelfProfile($field, $data, $student_application);
                break;
            case 'self-application':
                $value = StudentAlternative::fetchSelfApplication($field, $data, $student_application);
                break;
            default:
                die('unkown reporter: ' . $field->reporter);
        }

        // EXCEPTION NOTE:
        // ACTCHQUAL = array
        // CURACCDIS = array
        // PARLEAVE = array
        // SOBS = array

        // SOBS = array
//        switch ($field->code) {
//            case 'ACTCHQUAL':
//            case 'CURACCDIS':
//            case 'PARLEAVE':
//            case 'SOBS':
//                // split value by delineator :::
//                $values_array = explode(':::', $value);
//                foreach ($values_array AS $v) {
//                    $return .= Staff::processFieldNullHelper($field, $v);
//                }
//                break;
//            default:
                $return = StudentAlternative::processFieldNullHelper($field, $value);
//        }

        return $return;
    }

    public static function processFieldNullHelper($field, $value)
    {
        $return = '';
        $opentag = '<' . $field->code . '>';
        $closetag = '</' . $field->code . '>';
        if (strlen($value) > 0 || $field->nullable) {
            $return = $opentag.$value.$closetag;
        }
        if (strlen($value) < 1 && !$field->nullable && ($field->min_occurrence > 0)) {
            if ($field->code != 'APPSPEND') {
                //exception...
                session()->put('hesa_error', true);
                $msg = 'ERROR: field = `<strong>'.$field->name.' [ ' . $field->code . ' ]</strong>` is BLANK and should not be.';
                session()->push('hesa_errors', $msg);
                $return = $return = $opentag.'ERROR: IS NULL AND SHOULD NOT BE'.$closetag;
            }
        }

        return $return;
    }

    public static function fetchConstant($field)
    {
        return $field->default_value;
    }

    // these have specific calculations and thus are hard-coded
    public static function fetchCalculated($field, $data = null, $student_events = null, $student_application = null)
    {
        switch ($field->code) {
            case 'COURSEID':
            case 'OWNCOURSEID':
                $return = $data->course_group_id;
                break;
            case 'CTITLE':
                $return = $data->course_title;
                break;
            case 'OWNSTU':
                $return = $data->user_username;
                break;
            case 'NUMHUS':
                $return = $data->user_course_lookup_id;
                break;
            case 'POSTCODE':
                if (!$student_application->isEmpty()) {
                    $return = $student_application[0]->home_postcode;
                } else {
                    $return = $data->student_postcode;
                }
                break;
            case 'COMDATE':
                $return = date("Y-m-d", $data->degree_start);
                break;
            case 'OWNINST':
                $return = $data->user_course_lookup_id;
                break;
            case 'NOTACT':
                $return = '';
                if ($data->period_has_dormant) {
                    // ended early due to dormant period
                    $return = '1';
                }
                break;
            case 'PERIODEND':

                // NOTE:
                // when there is a dormant event, the period ends at the start of the dormant event,
                // and the next period starts at the end of the dormant event - so there is a gap between periods
                // as is shown in the HESA specs.

                if ($data->period_has_dormant) {
                    $return = $data->dormant_start;
                } else {
                    $return = $data->end;
                }
                break;
            case 'PERIODSTART':
                $return = $data->start;
                break;
            case 'STULOAD':
                $fte = (float) $data->fte;
                $return = ($fte*100);
                break;
            case 'YEARPRG':
                $return = $data->YEARPRG;
                break;
            case 'YEARSTU':
                $return = $data->YEARSTU;
                break;
            case 'SPLENGTH':
                 // get start date
                 $degree_start = $data->degree_start;
                 // get end date
                 $degree_end = $data->degree_end;
                 // work out diff
                 $length = $degree_end-$degree_start;
                 // convert seconds to days
                 $length = $length/60/60/24;

                 // remove any STUDENT_COURSE_DORMANT periods
                 $student_events_dormant = $student_events
                     ->where('type_id', '=', '102')
                     ->where('user_course_lookup_id', '=', $data->user_course_lookup_id);

                 foreach ($student_events_dormant AS $dormant) {
                     $event_start = $dormant->event_start;
                     $event_end = $dormant->event_end;
                     $event_expected_end = $dormant->event_expected_end;
                     $end = 0;
                     if ($event_end > 0) {
                         $end = $event_end;
                     } elseif ($event_expected_end > 0) {
                         $end = $event_expected_end;
                     }

                     if ($end > 0) {
                          $time_dormant = $end-$event_start;
                          $dt1 = new \DateTime("@0");
                          $dt2 = new \DateTime("@$time_dormant");
                          $time_dormant = $dt1->diff($dt2)->format('%a');
                          $length = $length-$time_dormant;
                     }
                 }

                 $return = $length;
                 break;
            default:
                die('ERROR: UNEXPECTED CALCULATED FIELD NAME: ' . $field->code);
        }

        return $return;
    }

    public static function fetchAdminCourse($field, $data = null)
    {
        switch ($field->code) {
            case 'SBJCA':
                $return = $data->subject_of_course;
                break;
            case 'SBJPCNT':
                $return = $data->subject_percentage;
                break;
            default:
                die('ERROR: UNEXPECTED AdminCourse FIELD NAME: ' . $field->code);
        }

        return $return;
    }

    public static function fetchSelfProfile($field, $data = null, $student_application = null)
    {
        switch ($field->code) {
            case 'BIRTHDTE':
                if ($student_application != null) {
                    // fetch from application form
                    $student_dob  = $student_application->dob;
                } else {
                    // fallback to user profile
                    $student_dob = $data->student_dob;
                    if ($student_dob < 1) {
                        $student_dob = '';
                    } else {
                        $student_dob = date("Y-m-d", $student_dob);
                    }
                }
                $return = $student_dob;
                break;
            case 'OWNSTU':
                $return = $data->user_username;
                break;
            case 'UCASPERID':
                $return = '';
                break;
            case 'ULN':
                //$return = $data->hesa_unique_learner_number;
                $return = '';
                break;
            case 'HUSID':
                $return = $data->hesa_unique_student_identifier;
                break;
            case 'DISABLE':
                if ($student_application != null) {
                    $return = $student_application->disabilities;
                } else {
                    $return = '';
                }
                break;
            case 'ETHNIC':
                if ($student_application != null) {
                    $return = $student_application->ethnicity;
                } else {
                    $return = '';
                }
                break;
            case 'GENDERID':
                if ($student_application != null) {
                    $return = $student_application->gender;
                } else {
                    $return = '99';
                }
                break;
            case 'RELBLF':
                 if ($student_application != null) {
                     $return = $student_application->religion;
                 } else {
                     $return = '';
                 }
                break;
            case 'SEXID':
                if ($student_application != null) {
                    $return = $student_application->sex_identifier;
                } else {
                    $return = '';
                }
                break;
            case 'SEXORT':
                if ($student_application != null) {
                    $return = $student_application->sexual_orientation;
                } else {
                    $return = '';
                }
                break;
            case 'DOMICILE':
                if (!$student_application->isEmpty()) {
                    $return = $student_application[0]->previous_country;
                } else {
                    $return = '';
                }
                break;
            case 'DISALL':
                $return = '9';
                if ($student_application != null) {
                    $disability = $student_application->disabilities;
                    if ((strlen($disability) > 0) && ($disability <> '00')) {
                        //has disability
                        //check if receiving allowance
                        $disability_allowance = $student_application->disability_allowance;
                        if (strlen($disability_allowance) > 0) {
                            $return = $disability_allowance;
                        }
                    }
                }
                break;
            default:
                die('ERROR: UNEXPECTED SelfProfile FIELD NAME: ' . $field->code);
        }

        return $return;
    }

    public static function fetchSelfApplication($field, $data = null, $student_application = null)
    {
//        echo ('$field->code:' . $field->code . '<br>');
        switch ($field->code) {
            case 'FNAMES':
                if ($student_application != null) {
                    // fetch from application form
                    $fnames = $student_application->first_names;
                } else {
                    $fnames = $data->user_first;
                }
                $return = $fnames;
                break;
            case 'SURNAME':
                if ($student_application != null) {
                    // fetch from application form
                    $surname = $student_application->surname;
                } else {
                    $surname = $data->user_last;
                }
                $return = $surname;
                break;
            case 'NATION':
                if ($student_application != null) {
                    $return = $student_application->nationality;
                } else {
                    $return = '';
                }
                break;
            case 'QUALENT3':
                $return = '';
                if ($student_application != null) {
                    if (count($student_application) > 0) {
                        $return = $student_application[0]->highest_qualification;
                    }
                }
                break;
            case 'QUALGRADE':
                $return = $data['grade'];
                break;
            case 'QUALSBJ':
                $return = $data['subject'];
                break;
            case 'QUALSIT':
                $return = $data['sitting'];
                break;
            case 'QUALTYPE':
                $return = $data['qualification'];
                break;
            case 'QUALYEAR':
                $return = $data['year'];
                break;
            case 'UCASSCHEMECODE':
                $return = '';
                break;
            case 'MODE':
                $mode = '';
                if ($student_application != null) {
                    $mode = $student_application->mode_of_study;
                    echo('$mode:'.$mode.'<br>');
                    die();
                }
                if (strpos($mode, 'Full Time') > -1) {
                    $mode = '01';
                } else {
                    $mode = '31';
                }
                $return = $mode;
                break;
            case 'MSTUFEE':
                if ($student_application != null) {
                    $return = $student_application->who_will_pay_fees;
                } else {
                    $return = '';
                }
                break;
            default:
                die('ERROR: UNEXPECTED SelfProfile FIELD NAME: ' . $field->code);
        }

        return $return;
    }

    public static function fetchAdminStudentEvent($field, $data, $student_events)
    {
         // innit
         $return = false;
         $ucl_id = $data->user_course_lookup_id;

         switch ($field->code) {
             case 'ENDDATE':

                 $student_events_completed = $student_events
                                         ->where('type_id', '=', '101')
                                         ->where('user_course_lookup_id', '=', $data->user_course_lookup_id);

                 // default
                 $degree_end = $data->degree_end;

                 if ($degree_end < 1) {
                     die('Error: schedule degree_end date is ZERO for course: ' . $data->course_name . '(' . $data->course_id . '), schedule: ' . $data->course_schedule_name . ' (' . $data->course_schedule_id . ')');
                 }

                 // explicit end in student event
                 if (count($student_events_completed) > 0) {
                     $degree_end = $student_events_completed->first()->event_start;
                 }

                 $degree_end = date("Y-m-d", $degree_end);

                 $return = $degree_end;

                 break;
             case 'FUNDCODE':
                 $return = StudentAlternative::extractEventData($student_events, $ucl_id, 110);
                break;
             case 'FUNDCOMP':
                 $return = StudentAlternative::extractEventData($student_events, $ucl_id,111);
                 break;
             case 'FUNDLEV':
                 $return = StudentAlternative::extractEventData($student_events, $ucl_id,112);
                 break;
             case 'GROSSFEE':
                 $return = StudentAlternative::extractEventData($student_events, $ucl_id,113);
                 break;
             case 'NETFEE':
                 $return = StudentAlternative::extractEventData($student_events, $ucl_id,114);
                 break;
             case 'RSNEND':
                 $return = StudentAlternative::extractEventData($student_events, $ucl_id,115);
                 break;
             case 'INITIATIVES':
                 // fetch initiatives
                 $initiatives = $student_events
                     ->where('type_id', '=', '120');
                 // allow up to three results
                 // if more than one, then inject tag closure/opening as a hack to pass more than one value through $return
                 // innit
                 $results = 0;
                 $return = '';

                 foreach ($initiatives AS $initiative) {
                    $results++;
                     if ($results > 3) {
                         // limit of three results
                         break;
                     }
                    if ($results > 1) {
                        $return .= '</INITIATIVES><INITIATIVES>';
                    }
                    $return .= $initiative->event_value;
                 }
                 break;
             case 'LOCSDY':
                 // fetch Location of study
                 $location_of_study = $student_events
                     ->where('type_id', '=', '121')->first();

                 if ($location_of_study) {
                     //RARE: Distance learning - Non-UK based student (funded)
                     //value = 9
                     $return = $location_of_study->event_value;
                 } else {
                     //DEFAULT TO 6 (UK BASED)
                     $return = '6';
                 }

                 break;
             case 'APPSPEND':
             case 'FINAMOUNT':
             case 'FINTYPE':
                 //exception - for these we are passing a delineated string
                 // e.g. "01::5000::01"
                 $temp_array = explode('::', $student_events);
                 $appspend = $temp_array[0];
                 $finamount = $temp_array[1];
                 $fintype = $temp_array[2];
                 if ($field->code == 'APPSPEND') { $return = $appspend; }
                 if ($field->code == 'FINAMOUNT') { $return = $finamount; }
                 if ($field->code == 'FINTYPE') { $return = $fintype; }
                 break;
             default:
                 die('ERROR: UNEXPECTED StudentEvent FIELD NAME: ' . $field->code);
         }

        return $return;
    }

    // helper to extract required values from event
    public static function extractEventData($student_events, $ucl_id, $id, $type = 'value')
    {
         $return = '';
         $object  = $student_events
             ->where('type_id', '=', $id)
             ->where('user_course_lookup_id', '=', $ucl_id);

         if (count($object) > 0) {
             switch ($type) {
                 case 'event_start':
                     $return = $object->first()->event_start;
                     $return = date("Y-m-d", $return);
                     break;
                 default:
                     $return = $object->first()->event_value;
             }
         }
         return $return;
    }
}
