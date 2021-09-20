<?php

namespace App\Models\Hesa;

use App\Models\Hesa\Staff\Validation;

class Staff
{
    public static function processField($field, $data = null, $staff_contracts = null)
    {
        $return = '';
        $value = '';

        switch ($field->reporter) {
            case 'constant':
                $value = Staff::fetchConstant($field);
                break;
            case 'calculated':
                $value = Staff::fetchCalculated($field, $data);
                break;
            case 'self':
            case 'admin-staff':
                $value = Staff::fetchStaffRecord($field, $data, $staff_contracts);
                break;
            case 'admin-staff-event':
                $value = Staff::fetchAdminStaffEvent($field, $data, $staff_contracts);
                break;
        }

        // EXCEPTION NOTE:
        // ACTCHQUAL = array
        // CURACCDIS = array
        // PARLEAVE = array
        // SOBS = array

        // SOBS = array
        switch ($field->code) {
            case 'ACTCHQUAL':
            case 'CURACCDIS':
            case 'PARLEAVE':
            case 'SOBS':
                // split value by delineator :::
                $values_array = explode(':::', $value);
                foreach ($values_array AS $v) {
                    $return .= Staff::processFieldNullHelper($field, $v);
                }
                break;
            default:
                $return = Staff::processFieldNullHelper($field, $value);
        }

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
            session()->push('hesa_error', true);
            $msg = 'ERROR: field = `<strong>'.$field->name.' [ ' . $field->code . ' ]</strong>` is BLANK and should not be.';
            session()->push('hesa_errors', $msg);
            $return = $return = $opentag.'ERROR: IS NULL AND SHOULD NOT BE'.$closetag;
        }
        return $return;
    }

    public static function fetchConstant($field)
    {
        return $field->default_value;
    }

    // these have specific calculations and thus are hard-coded
    public static function fetchCalculated($field, $data = null)
    {
        switch ($field->code) {
            case 'OWNSTAFFID':
            case 'STAFFID':
                $return = $data->user_id;
                break;
            case 'OWNCONTID':
            case 'CONTID':
                $return = $data->staff_contract_id;
                break;
            default:
                die('ERROR: UNEXPECTED CALCULATED FIELD NAME: ' . $field->code);
        }

        return $return;
    }

    public static function fetchStaffRecord($field, $data = null, $staff_contracts = null)
    {
        // ACTCHQUAL
        // BIRTHDTE
        // DISABLE
        // ETHNIC
        // GENREASSIGN
        // HQHELD
        // NATION
        // ORCID
        // PREVEMP
        // PREVHEI
        // RELBLF
        // SEXID
        // SEXORT

        // admin reported
        // CURACCDIS

        // get value
        $fieldname = $field->code;
        $value = $data->$fieldname;

        if ($field->hesa_validation_id_array <> '') {
            // VALIDATION...
            $value = Validation::validate($value, $field, $data, $staff_contracts);
        }
        return $value;
    }

    public static function fetchAdminStaffEvent($field, $data = null, $staff_contracts = null)
    {
        switch ($field->code) {
            // > Person
            case 'ACTLEAVE':
            case 'DATELEFT':
            case 'GOVFLAG':
            case 'LOCLEAVE':
            case 'PARLEAVE':
            // > Person > Contract
            case 'ACEMPFUN':
            case 'APPRENTICESHIP':
            case 'CONFTE':
            case 'ENDCON':
            case 'HEIJOINT':
            case 'HOURLYPAID':
            case 'LEVELS':
            case 'MOEMP':
            case 'RESAST':
            case 'RESCON':
            case 'SALREF':
            case 'SOBS':
            case 'STARTCON':
            case 'TERMS':
            case 'ZEROHRS':
            // > Person > Contract > activity
            case 'ACTSOC':
            case 'CCENTRE':
            case 'CCPROP':
                $fieldname = $field->code;
                $return = $data->$fieldname;
                break;
            // > Person > Governor
            case 'ENDGOV':
                $return = '';
                if (strlen($data->event_end) > 0) {
                    // convert timestamp to string
                    $return = date('Y-m-d', $data->event_end);
                }
                break;
            case 'EXPENDGOV':
                $return = '';
                if (strlen($data->event_expected_end) > 0) {
                    // convert timestamp to string
                    $return = date('Y-m-d', $data->event_expected_end);
                }
                break;
            case 'STARTGOV':
                // convert timestamp to string
                $return = date('Y-m-d', $data->event_start);
                break;
            default:
                die('ERROR: UNEXPECTED FIELD NAME: ' . $field->code);
        }

        // CHECK FOR VALIDATION...
        if ($field->hesa_validation_id_array <> '') {
            // VALIDATION...
            $return = Validation::validate($return, $field, $data, $staff_contracts);
        }

        return $return;
    }

}