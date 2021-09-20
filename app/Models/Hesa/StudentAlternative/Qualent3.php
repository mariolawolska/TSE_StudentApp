<?php

namespace App\Models\Hesa\StudentAlternative;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualent3 extends Model {

    // use HasFactory;
    protected $table = 'hesa_student_alternative_qualent3';

    public static function getStudenAlternativeQualification() {
        return Qualent3::orderBy('id', 'ASC')->get();
    }

}
