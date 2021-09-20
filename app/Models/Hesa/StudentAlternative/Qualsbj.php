<?php

namespace App\Models\Hesa\StudentAlternative;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualsbj extends Model {

    // use HasFactory;
    protected $table = 'hesa_student_alternative_qualsbj';

    public static function getStudenAlternativeQualsbj($array = false) {
        $qualsbjCollection = Qualsbj::orderBy('id', 'ASC')->get();
        if ($array) {
            $qualsbjArray = [];
            foreach ($qualsbjCollection as $qualsbj) {
                $qualsbjArray[$qualsbj->hesa_code] = $qualsbj->hesa_label;
            }
            return $qualsbjArray;
        } else {
            return $qualsbjCollection;
        }
    }

}
