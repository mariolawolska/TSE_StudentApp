<?php

namespace App\Models\Hesa\Staff;

use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    // core function
    public static function validate($value, $field, $data = null, $staff_contracts = null)
    {
        // NOTE: all validation is hard coded

        switch ($field->code) {
            case 'ACTCHQUAL':
                $return = Validation::validationACTCHQUAL($value, $staff_contracts);
                break;
            case 'ACTLEAVE':
            case 'LOCLEAVE':
                $return = Validation::validationLEFT($value, $data, $staff_contracts);
                break;
            case 'CURACCDIS':
                $return = Validation::validationCURACCDIS($value, $staff_contracts);
                break;
            case 'DATELEFT':
                $return = Validation::validationDATELEFT($value, $staff_contracts);
                break;
            case 'HQHELD':
                $return = Validation::validationHQHELD($value, $data, $staff_contracts);
                break;
            case 'PREVEMP':
                $return = Validation::validationPREVEMP($value, $staff_contracts);
                break;
            case 'PREVHEI':
                $return = Validation::validationPREVHEI($value, $data, $staff_contracts);
                break;
            case 'ACEMPFUN':
                $return = Validation::validationACEMPFUN($value, $data);
                break;
            case 'RESAST':
                $return = Validation::validationRESAST($value, $data);
                break;
            case 'RESCON':
                $return = Validation::validationRESCON($value, $data);
                break;
            case 'SALREF':
            case 'SOBS':
            case 'ENDCON':
            case 'HEIJOINT':
                $return = Validation::validationIsTermsOneOrTwo($value, $data);
                break;
        default:
            die('ERROR: NO VALIDATION CODED FOR: ' . $field->code);
        }

        return $return;
    }

    // specific functions
    public static function validationACTCHQUAL($value, $staff_contracts)
    {
        // Contract.ACEMPFUN is coded 1 or 3 and Contract.TERMS is coded 1 or 2).
        // Academic employment function
        //    1 = Academic contract that is teaching only
        //    3 = Academic contract that is both teaching and research
        //    1 = Open-ended/Permanent
        //    2 = Fixed-term}

        $isValid = false;
        foreach ($staff_contracts AS $staff_contract) {
            $ACEMPFUN = $staff_contract->ACEMPFUN;
            $TERMS = $staff_contract->TERMS;
            if ((($ACEMPFUN == '1') || ($ACEMPFUN == '3')) && (($TERMS == '1') || ($TERMS == '2'))) {
                $isValid = true;
            }
        }
        if ($isValid) {
            $return = $value;
        } else {
            $return = '';
        }

        return $return;
    }
    public static function validationCURACCDIS($value, $staff_contracts)
    {
        // All staff where any (Contract.ACEMPFUN is coded 1, 2 or 3 and Contract.TERMS is coded 1 or 2).
        // Academic employment function
        //    1 = Academic contract that is teaching only
        //    2 = Academic contract that is research only
        //    3 = Academic contract that is both teaching and research
        //    1 = Open-ended/Permanent
        //    2 = Fixed-term}

        $isValid = false;
        foreach ($staff_contracts AS $staff_contract) {
            $ACEMPFUN = $staff_contract->ACEMPFUN;
            $TERMS = $staff_contract->TERMS;
            if ((($ACEMPFUN == '1') || ($ACEMPFUN == '2') || ($ACEMPFUN == '3')) && (($TERMS == '1') || ($TERMS == '2'))) {
                $isValid = true;
            }
        }
        if ($isValid) {
            $return = $value;
        } else {
            $return = '';
        }

        return $return;
    }
    public static function validationDATELEFT($value, $staff_contracts)
    {
        // any Contract.TERMS is coded 1 or 2
        // and all Contract.ENDCON are not null,
        // unless any Contract.RESCON are coded 1 or 2.

        $isValid = true;

        foreach ($staff_contracts AS $staff_contract) {
            $TERMS = $staff_contract->TERMS;
            $ENDCON = $staff_contract->ENDCON;
            $RESCON = $staff_contract->RESCON;
            if ((($TERMS == '1') || ($TERMS == '2')) && ($ENDCON == '') && ($RESCON != '1') && ($RESCON != '2')) {
                //if you're still active, or left but have new contract
                $isValid = false;
            }
        }

        if ($isValid) {
            $return = $value;
        } else {
            $return = '';
        }

        return $return;

    }
    public static function validationHQHELD($value, $staff, $staff_contracts)
    {
        //All staff where any Activity.ACTSOC is in SOC2010 Major Groups 1, 2 or 3
        // and Contract.TERMS is coded 1 or 2.
        // OR
        // Compulsory for all staff where Person.GOVFLAG = 1.

        $GOVFLAG = $staff->GOVFLAG;

        if ($GOVFLAG == '1') {
            $isValid = true;
        } else {
            $isValid = Validation::subValidationTermsAndMajor($staff_contracts);
        }

        if ($isValid) {
            $return = $value;
        } else {
            $return = '';
        }

        return $return;
    }
    public static function validationPREVEMP($value, $staff_contracts)
    {
        //All staff where any (Activity.ACTSOC is in SOC2010 Major Groups 1, 2 or 3
        // and Contract.TERMS is coded 1 or 2).

        $isValid = Validation::subValidationTermsAndMajor($staff_contracts);

        if ($isValid) {
            $return = $value;
        } else {
            $return = '';
        }

        return $return;
    }
    public static function validationPREVHEI($value, $staff, $staff_contracts)
    {
        //All staff where any Activity.ACTSOC is in SOC2010 Major Groups 1, 2 or 3
        // and Person.PREVEMP is coded 01

        $isValid = false;

        $PREVEMP = $staff->PREVEMP;

        if ($PREVEMP == '01') {
            foreach ($staff_contracts as $staff_contract) {
                // load activity
                $contract_activities = Record::fetchContractActivities($staff_contract->staff_contract_id);
                foreach ($contract_activities as $contract_activity) {
                    $ACTSOC = $contract_activity->ACTSOC;
                    if (strlen($ACTSOC) > 0) {
                        // Major group
                        $majorGroup = $ACTSOC[0];
                        if (preg_match('([123])', $majorGroup) === 1) {
                            $isValid = true;
                        }
                    }
                }
            }
        }

        if ($isValid) {
            $return = $value;
        } else {
            $return = '';
        }

        return $return;
    }
    public static function validationACEMPFUN($value, $staff_contract)
    {
        //All staff where any Activity.ACTSOC is in SOC2010 Major Groups 1, 2 or 3
        $isValid = false;

        // load activity
        $contract_activities = Record::fetchContractActivities($staff_contract->staff_contract_id);
        foreach ($contract_activities as $contract_activity) {
            $ACTSOC = $contract_activity->ACTSOC;
            if (strlen($ACTSOC) > 0) {
                // Major group
                $majorGroup = $ACTSOC[0];
                if (preg_match('([123])', $majorGroup) === 1) {
                    $isValid = true;
                }
            }
        }

        if ($isValid) {
            $return = $value;
        } else {
            $return = '';
        }

        return $return;
    }
    public static function validationRESAST($value, $staff_contract)
    {
        //All staff where Contract.ACEMPFUN is coded 2
        // and Contract.TERMS is coded 1 or 2.

        $isValid = false;

        $TERMS = $staff_contract->TERMS;
        $ACEMPFUN = $staff_contract->ACEMPFUN;
        if ((($TERMS == '1') || ($TERMS == '2')) && ($ACEMPFUN == '2')) {
            $isValid = true;
        }

        if ($isValid) {
            $return = $value;
        } else {
            $return = '';
        }

        return $return;
    }
    public static function validationRESCON($value, $staff_contract)
    {
        //All contracts where Contract.ENDCON is not null

        $isValid = false;

        $ENDCON = $staff_contract->ENDCON;
        if (strlen($ENDCON) > 0) {
            $isValid = true;
        }

        if ($isValid) {
            $return = $value;
        } else {
            $return = '';
        }

        return $return;
    }

    // used by more than one
    public static function validationIsTermsOneOrTwo($value, $staff_contract)
    {
        //All contracts where Contract.TERMS is coded 1 or 2 and Contract.SPOINT has not been returned.

        $isValid = false;

        $TERMS = $staff_contract->TERMS;
        if (($TERMS == '1') || ($TERMS == '2')) {
            $isValid = true;
        }

        if ($isValid) {
            $return = $value;
        } else {
            $return = '';
        }

        return $return;
    }
    public static function validationLEFT($value, $staff, $staff_contracts)
    {
        // All staff where any (Activity.ACTSOC is in SOC2010 Major Groups 1, 2 or 3
        // and Person.DATELEFT is not null and
        // Contract.TERMS is coded 1 or 2 and
        // no Contract.RESCON is coded 8).

        $isValid = false;

        $DATELEFT = $staff->DATELEFT;
        if (strlen($DATELEFT) > 0) {
            foreach ($staff_contracts AS $staff_contract) {
                $TERMS = $staff_contract->TERMS;
                $RESCON = $staff_contract->RESCON;
                if ((($TERMS == '1') || ($TERMS == '2')) && ($RESCON != '8')) {
                    // load activity
                    $contract_activities = Record::fetchContractActivities($staff_contract->staff_contract_id);
                    foreach ($contract_activities as $contract_activity) {
                        $ACTSOC = $contract_activity->ACTSOC;
                        if (strlen($ACTSOC) > 0) {
                            // Major group
                            $majorGroup = $ACTSOC[0];
                            if(preg_match('([123])', $majorGroup) === 1) {
                                $isValid = true;
                            }
                        }
                    }
                }
            }
        }
        if ($isValid) {
            $return = $value;
        } else {
            $return = '';
        }

        return $return;

    }

    //reusable sections
    public static function subValidationTermsAndMajor($staff_contracts)
    {
        $isValid = false;

        foreach ($staff_contracts AS $staff_contract) {
            $TERMS = $staff_contract->TERMS;
            if (($TERMS == '1') || ($TERMS == '2')) {
                // load activity
                $contract_activities = Record::fetchContractActivities($staff_contract->staff_contract_id);
                foreach ($contract_activities as $contract_activity) {
                    $ACTSOC = $contract_activity->ACTSOC;
                    if (strlen($ACTSOC) > 0) {
                        // Major group
                        $majorGroup = $ACTSOC[0];
                        if(preg_match('([123])', $majorGroup) === 1) {
                            $isValid = true;
                        }
                    }
                }
            }
        }

        return $isValid;

    }

}
