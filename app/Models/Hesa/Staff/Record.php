<?php

namespace App\Models\Hesa\Staff;

//use Illuminate\Database\Eloquent\Model;
use App\Models\Hesa\Staff\Record\Fields;
use App\Models\Hesa\Collections;
use App\Models\Hesa\Staff;
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

        $this->loadCollection (3);
    }

    public function loadCollection($id)
    {
        $this->collection = Collections::findOrFail($id);
        if ($this->collection->type <> 'staff') {
            die('Error: not a staff collection id...');
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

        $xml =  '<Institution>';
        $fields = $this->fields;

        // Institution
        // Institution > Person
        // Institution > Person > Contract
        // Institution > Person > Contract > Activity
        // Institution > Person > Governor

        //  2x constant fields
            $institution = $fields->where('parent', 'institution');
            // $institution->where('code', 'RECID');
            // $institution->where('code', 'UKPRN');
            foreach ($institution AS $field) {
                $xml .= Staff::processField($field);
            }

        $person = $fields->where('parent', 'person');
        $contract = $fields->where('parent', 'contract');
        $activity = $fields->where('parent', 'activity');
        $governor = $fields->where('parent', 'governor');

        $staff_members = $this->fetchAllStaff();

        foreach ($staff_members AS $staff) {

            // insert error header for this staff member
            $msg = 'Hesa Staff: ';
            $msg .= ' <strong>' . $staff->user_first . ' ';
            $msg .= ' ' . $staff->user_last . '</strong> ';
            $msg .= '[ username: ' . $staff->user_username . ' ]';
            $msg .= '[ user_id: ' . $staff->user_id . ' ]';
            session()->push('hesa_errors', $msg);

            $xml .= '<Person>';

            // load contracts
            $staff_contracts = $this->fetchContracts($staff->user_id);

            //cycle through all person fields
            foreach ($person AS $p) {
                $xml .= Staff::processField($p, $staff, $staff_contracts);
            }

            // for each contract
            foreach ($staff_contracts AS $staff_contract) {
                // minimum of 1 contract
                $xml .= '<Contract>';
                foreach ($contract as $c) {
                    $xml .= Staff::processField($c, $staff_contract, $staff);
                }

                // load activity
                $contract_activities = $this->fetchContractActivities($staff_contract->staff_contract_id);
                foreach ($contract_activities as $contract_activity) {
                    $xml .= '<Activity>';
                    foreach ($activity as $a) {
                        $xml .= Staff::processField($a, $contract_activity);
                    }
                    $xml .= '</Activity>';
                }
                $xml .= '</Contract>';
            }

            // load governor data
            $governor_data = $this->fetchGovernorData($staff->user_id);
//            if (!$governor_data->isEmpty()) {
            foreach ($governor_data as $gov) {
                $xml .= '<Governor>';
                foreach ($governor as $g) {
                    $xml .= Staff::processField($g, $gov);
                }
                $xml .= '</Governor>';
            }

            $xml .= '</Person>';
        }
        $xml .= '</Institution>';

        return Hesa::renderXML($this->collection, $xml, 'file');
    }

    public function fetchAllStaff()
    {
        // NOTE:
        // ACTCHQUAL = array
        // CURACCDIS = array
        // PARLEAVE = array
        return DB::connection('mysql_vle')
            ->table('user AS u')
            ->select(
                'u.*',
                'sr.ACTCHQUAL',
                'sr.BIRTHDTE',
                'sr.DISABLE',
                'sr.ETHNIC',
                'sr.GENREASSIGN',
                'sr.HQHELD',
                'sr.NATION',
                'sr.ORCID',
                'sr.PREVEMP',
                'sr.PREVHEI',
                'sr.RELBLF',
                'sr.SEXID',
                'sr.SEXORT',
                'sr.CURACCDIS',
                'sr.ACEMPFUN',

                'sr.ACTLEAVE',
                'sr.DATELEFT',
                'sr.GOVFLAG',
                'sr.LOCLEAVE',
                'sr.PARLEAVE'
            )
            ->leftjoin('staff_record AS sr', 'sr.user_id', '=', 'u.user_id')
            ->where('u.deleted', '=', '0')
            ->where('u.user_type_id', '>', '10') // all staff
            ->where('u.omit_from_hesa_report', '=', '0') // only those flagged to be reported
            ->get();
    }

    public function fetchContracts($user_id)
    {
        // NOTE:
        // SOBS = array

        $start = $this->collection->period_start;
        $end = $this->collection->period_end;

        // \DB::connection('mysql_vle')->enableQueryLog();

        // echo "<pre>";
            // var_dump(\DB::connection('mysql_vle')->getQueryLog());
            // echo "</pre>";

        return DB::connection('mysql_vle')
            ->table('staff_contract AS sc')
            ->where('sc.user_id', '=', $user_id)
            ->where('sc.deleted', '=', '0')
            ->where(function ($query) use ($start, $end){
                // finished within period
                return $query->where(
                    function ($query) use ($start, $end){
                        return $query->where('ENDCON', '<=', $end)
                                    ->where('ENDCON', '>=', $start);
                    })
                    // started (not finished) before period end (still active)
                    ->orWhere(
                    function ($query) use ($start, $end){
                        return $query->where('STARTCON', '<=', $end)
                                    ->where('is_active', '=', '1');
                    });
            })
            ->get();

    }

    public static function fetchContractActivities($staff_contract_id)
    {
        return DB::connection('mysql_vle')
            ->table('staff_contract_activity AS sca')
            ->where('sca.staff_contract_id', '=', $staff_contract_id)
            ->where('sca.deleted', '=', '0')
            ->get();
    }

    public function fetchGovernorData($user_id)
    {
        $start = strtotime($this->collection->period_start);
        $end = strtotime($this->collection->period_end);

//         \DB::connection('mysql_vle')->enableQueryLog();

        //             echo "<pre>";
//             var_dump(\DB::connection('mysql_vle')->getQueryLog());
//             echo "</pre>";
//             die();

        return DB::connection('mysql_vle')
            ->table('user_event AS ue')
            ->where('ue.user_id', '=', $user_id)
            ->where('ue.deleted', '=', '0')
            ->where(function ($query) use ($start, $end){
                // finished within period
                return $query->where(
                    // currently a governor
                    function ($query) use ($start, $end){
                        return $query->where('type_id', '=', '50')
                                    ->where('event_end', '=', null);
                    })
                    // ended governor this period
                    ->orWhere(
                    function ($query) use ($start, $end){
                        return $query->where('type_id', '=', '50')
                                    ->where('event_end', '>=', $start)
                                    ->where('event_end', '<=', $end);
                    });
            })
            ->limit(1)
            ->orderBy('event_start','DESC')
            ->get();
    }

}
