<?php

namespace App\Models\Hesa\Aggregate;

//use Illuminate\Database\Eloquent\Model;

use App\Models\Hesa\Aggregate\Record\Fields;
use App\Models\Hesa\Collections;
use App\Models\Hesa\Aggregate;
use App\Models\Hesa;
use App\Models\StudentApplications;
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

        $this->loadCollection (1);
    }

    public function loadCollection($id)
    {
        $this->collection = Collections::findOrFail($id);
        if ($this->collection->type <> 'aggregate') {
            die('Error: not an aggregate offshore collection id...');
        }
        $this->fields = Fields::where('hesa_collection_id',$id)->orderBy('code','asc')->get();

        return $id;
    }

    public function generateXml()
    {
        $xml = '';
        $xml .= '<Provider>';
        $fields = $this->fields;

        //  Provider >
        //  2x constant fields
            $provider = $fields->where('parent', 'provider');
            // $provider->where('code', 'RECID');
            // $provider->where('code', 'UKPRN');
            foreach ($provider AS $field) {
                $xml .= Aggregate::processField($this->collection, $field);
            }

        // Provider > Provision
        // for each country
        // constants
        //            $fields->where('code', 'LEVEL');
        //            $fields->where('code', 'TYPE');

        $provision = $fields->where('parent', 'provision');

        $COUNTRY = '';
        $HEADCOUNTSSC = 0; // completed
        $HEADCOUNTDS = 0; // dormant
        $HEADCOUNTSW = 0; // withdrawn
        $HEADCOUNTSCS = 0; // continuing
        $data = [];
        $tempdata = [];

        $students = $this->fetchAllAggregateDataForPeriod();
        foreach ($students AS $student) {
            if ($student->iso_two_hesa <> $COUNTRY) {
                if (strlen($COUNTRY) > 0) {
                    // add to array
                    $tempdata['COUNTRY'] = $COUNTRY;
                    $tempdata['HEADCOUNTSSC'] = $HEADCOUNTSSC;
                    $tempdata['HEADCOUNTDS'] = $HEADCOUNTDS;
                    $tempdata['HEADCOUNTSW'] = $HEADCOUNTSW;
                    $tempdata['HEADCOUNTSCS'] = $HEADCOUNTSCS;
                    $data[] = $tempdata;
                }

                // reset
                $COUNTRY = $student->iso_two_hesa;
                $HEADCOUNTSSC = 0;
                $HEADCOUNTDS = 0;
                $HEADCOUNTSW = 0;
                $HEADCOUNTSCS = 0;
            }

            // loop through and create results array
            if ($student->HEADCOUNTSW == 1) {
                $HEADCOUNTSW++; // withdrawn
            } elseif ($student->HEADCOUNTDS == 1) {
                $HEADCOUNTDS++; // dormant
            } elseif ($student->HEADCOUNTSSC == 1) {
                $HEADCOUNTSSC++; // completed
            } else {
                $HEADCOUNTSCS++;
            }
        }

        foreach ($data as $provision_data) {
            // fetch all foreign students during period - order by country
            $xml .= '<Provision>';

            foreach ($provision as $field) {
                $xml .= Aggregate::processField($this->collection, $field, $provision_data);
            }
            $xml .= '</Provision>';

        }
        $xml .= '</Provider>';

        return Hesa::renderXML($this->collection, $xml, 'file');
    }

    public function fetchAllAggregateDataForPeriod()
    {
        // IMPORTANT:
        Hesa::syncStudentApplicationsToVle();

        $start = strtotime($this->collection->period_start);
        $end = strtotime($this->collection->period_end);

        $users = \DB::connection('mysql_vle')
            ->table('user_course_lookup AS ucl')
            ->select(

                \DB::raw('IF((cs.degree_end >= ' . $start . ' AND cs.degree_end <= ' . $end . '), 1, IF (ue_completed.user_event_id > 0,1,0)) AS HEADCOUNTSSC'),
                \DB::raw('IF(ue_dormant.user_event_id > 0 AND ucl.is_dormant = 1, 1, 0) AS HEADCOUNTDS'),
                \DB::raw('IF(ue_withdrawn.user_event_id > 0 AND ucl.is_withdrawn = 1, 1, 0) AS HEADCOUNTSW'),
                'ct.iso_two_hesa'
            )
            ->leftjoin('user AS u', 'u.user_id', '=', 'ucl.user_id')
            ->leftjoin('student_record AS sr', 'sr.user_id', '=', 'u.user_id')
            ->leftjoin('course AS c', 'c.course_id', '=', 'ucl.course_id')
            ->leftjoin('course_schedule AS cs', 'cs.course_schedule_id', '=', 'ucl.course_schedule_id')
            ->leftjoin('country AS ct', 'ct.country_id', '=', 'sr.student_country_id')
            ->leftjoin('tmp_student_applications AS sa', 'sa.user_course_lookup_id', '=', 'ucl.user_course_lookup_id')
            ->leftjoin('user_event AS ue_completed', function($join) use ($start, $end){
                $join->on('ue_completed.deleted', '=', \DB::raw(0));
                $join->on('ue_completed.user_id', '=', 'u.user_id');
                $join->on('ue_completed.event_start', '>=', \DB::raw($start));
                $join->on('ue_completed.event_end', '<=', \DB::raw($end));
                $join->on('ue_completed.user_course_lookup_id', '=', 'ucl.user_course_lookup_id');
                $join->on('ue_completed.type_id', '=', \DB::raw(101));
            })
            ->leftjoin('user_event AS ue_dormant', function($join) use ($start, $end){
                $join->on('ue_dormant.deleted', '=', \DB::raw(0));
                $join->on('ue_dormant.user_id', '=', 'u.user_id');
                $join->on('ue_dormant.event_start', '>=', \DB::raw($start));
                $join->on('ue_dormant.user_course_lookup_id', '=', 'ucl.user_course_lookup_id');
                $join->on('ue_dormant.type_id', '=', \DB::raw(102));
            })
            ->leftjoin('user_event AS ue_withdrawn', function($join) use ($start, $end){
                $join->on('ue_withdrawn.deleted', '=', \DB::raw(0));
                $join->on('ue_withdrawn.user_id', '=', 'u.user_id');
                $join->on('ue_withdrawn.event_start', '>=', \DB::raw($start));
                $join->on('ue_withdrawn.user_course_lookup_id', '=', 'ucl.user_course_lookup_id');
                $join->on('ue_withdrawn.type_id', '=', \DB::raw(103));
            })
            ->where('cs.degree_start', '<=', $end)
            ->where('cs.degree_end', '>=', $start)
            ->where('c.course_type_id', '=', '2')
            ->where('ucl.deleted', '=', '0')
            ->where('u.deleted', '=', '0')
            ->where('u.user_type_id', '=', '10') // student
            ->where('u.omit_from_hesa_report', '=', '0')
            ->where('c.deleted', '=', '0')
            ->where('cs.deleted', '=', '0')
            ->where('sr.deleted', '=', '0')
            ->where('ct.deleted', '=', '0')
//            ->where('sr.student_country_id', '<>', '228')
//    		->whereNotIn('sa.student_country_id', array(228,229,230,250))
    		->where('sa.iso_two_hesa', '<>', 'GB')
            ->orderBy('iso_two_hesa', 'ASC')
            ->get();

        return $users;
    }
}
