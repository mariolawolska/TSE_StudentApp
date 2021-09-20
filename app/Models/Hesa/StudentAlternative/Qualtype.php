<?php

namespace App\Models\Hesa\StudentAlternative;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualtype extends Model {

    // use HasFactory;
    protected $table = 'hesa_student_alternative_qualtype';

    public static function getStudenAlternativeQualtype($array = false) {
        $qualtypeCollection = Qualtype::orderBy('id', 'ASC')->get();
        if ($array) {
            $qualtypeArray = [];
            foreach ($qualtypeCollection as $qualtype) {
                $qualtypeArray[$qualtype->hesa_code] = $qualtype->hesa_label;
            }
            return $qualtypeArray;
        } else {
            return $qualtypeCollection;
        }
    }

}
