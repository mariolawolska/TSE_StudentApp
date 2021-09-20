<?php

namespace App\Models\Hesa;

class Aggregate
{
    public static function processField($collection, $field, $provision_data = null)
    {
        $return = '';
        $opentag = '<' . $field->code . '>';
        $value = '';
        $closetag = '</' . $field->code . '>';

        switch ($field->reporter) {
            case 'constant':
                $value = Aggregate::fetchConstant($field);
                break;
            case 'calculated':
                $value = Aggregate::fetchCalculated($collection, $field, $provision_data);
                break;
            case 'admin-student-event':
                $value = Aggregate::fetchAdminStudentEvent($collection, $field, $provision_data);
                break;
        }

        if (strlen($value) > 0 || $field->nullable) {
            $return = $opentag.$value.$closetag;
        }

//        check if empty field when not allowed
//        if (strlen($value) < 1 && !$field->nullable) {
//            echo "ERROR - EMPTY FIELD:<br><br><pre>";
//            echo ('$collection->code:' . $collection->code . '<br>');
//            echo ('$collection->name:' . $collection->name . '<br>');
//            echo ('$field->code:' . $field->code . '<br>');
//            echo ('$field->name:' . $field->name . '<br>');
//
//            die();
//        }

        return $return;
    }

    public static function fetchConstant($field)
    {
        return $field->default_value;
    }

    // these have specific calculations and thus are hard-coded
    public static function fetchCalculated($collection, $field, $provision_data = null)
    {
        // all calculations have been done in SQL
        return $provision_data[$field->code];
    }

    // look up data in the admin student event table - currently does not exist
    public static function fetchAdminStudentEvent($collection, $field, $provision_data = null)
    {
        // all calculations have been done in SQL
        return $provision_data[$field->code];
    }
}