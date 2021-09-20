<?php

namespace App\Models\Hesa\Student;

//use Illuminate\Database\Eloquent\Model;
use App\Models\Hesa\Student\Record\Fields;
use App\Models\Hesa\Collections;
use App\Models\Hesa\Student;
use App\Models\Hesa;
use Illuminate\Support\Facades\DB;

class Record
{
    public $fields;
    public $collection;

    public function __construct()
    {
        // :Todo
        // hardcoded for now (Mar 2021)
        // this will need to respond to specific periods

        $this->loadCollection (2);
    }

    public function loadCollection($id)
    {
        $this->collection = Collections::findOrFail($id);
        if ($this->collection->type <> 'student') {
            die('Error: not a student collection id...');
        }
        $this->fields = Fields::where('hesa_collection_id',$id)->orderBy('code','asc')->get();

        return $id;
    }

    public function generateXml()
    {
        $xml = '<Institution>';
        $fields = $this->fields;

        // Institution
        // Institution > Course
        // Institution > Course > Course Subject
        // Institution > Course > Delivery organisation and location

        // Institution > Module
        // Institution > Module > Module subject

        // Institution > Student
        // Institution > Student > Instance
        // Institution > Student > Instance > Entry profile
        // Institution > Student > Instance > Entry profile > Qualifications on entry
        // Institution > Student > Instance > Financial support
        // Institution > Student > Instance > ITT placement
        // Institution > Student > Instance > Mobility:
        // Institution > Student > Instance > Qualifications awarded:
        // Institution > Student > Instance > REF data:
        // Institution > Student > Instance > Student on module:

        //  3x constant fields
            $institution = $fields->where('parent', 'institution');
            // $institution->where('code', 'INSTAPP');
            // $institution->where('code', 'RECID');
            // $institution->where('code', 'UKPRN');
            foreach ($institution AS $field) {
                $xml .= Student::processField($field);
            }

//            fetch all courses

//        for each course

                    $xml .= '<Course>';

                        // course data

                        // lookup subject(s)
                        $xml .= '<CourseSubject>';

                        // course subject data (0 <> 5)

                        $xml .= '</CourseSubject>';

                        // lookup Delivery organisation and location(s)
                        $xml .= '<DeliveryOrganisationAndLocation>';

                        // Delivery organisation and location data ( >=0)

                        $xml .= '</DeliveryOrganisationAndLocation>';

                    $xml .= '</Course>';

        $course = $fields->where('parent', 'course');
        $course_subject = $fields->where('parent', 'course subject');
        $delivery_organisation_and_location = $fields->where('parent', 'delivery organisation and location');


        $xml .= '</Institution>';

        return Hesa::renderXML($this->collection, $xml, 'file');

//        Todo: change to file
//        return Hesa::renderXML($this->collection, $xml, 'file');

    }

    public function fetchAllCourses()
    {
        // NOTE:
        // AWARDBOD = array

        // Todo: what courses to fetch?
        // fetch course_group where hesa_reporting = 1
        // and schedule for course_group course(s) [where omit_from_hesa_ = 0], spans the reporting period

    }

}
