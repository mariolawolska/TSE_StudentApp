<?php

namespace App\Models\Hesa;

use App\Models\Hesa\Student\Validation;

class Student
{
    public static function processField($field, $data = null, $staff_contracts = null)
    {
        $return = '';
        $value = '';

        switch ($field->reporter) {
            case 'constant':
                $value = Student::fetchConstant($field);
                break;
//            case 'calculated':
//                $value = Student::fetchCalculated($field, $data);
//                break;
//            case 'self':
//            case 'admin-staff':
//                $value = Student::fetchStaffRecord($field, $data, $staff_contracts);
//                break;
//            case 'admin-staff-event':
//                $value = Student::fetchAdminStaffEvent($field, $data, $staff_contracts);
//                break;
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
                $return = Student::processFieldNullHelper($field, $value);
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
        return $return;
    }

    public static function fetchConstant($field)
    {
        return $field->default_value;
    }

}
