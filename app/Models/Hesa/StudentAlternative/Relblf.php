<?php

namespace App\Models\Hesa\StudentAlternative;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relblf extends Model {

    // use HasFactory;
    protected $table = 'hesa_student_alternative_relblf';

    public static function getStudentReligion() {
        return Relblf::orderBy('hesa_label', 'ASC')->get();
    }

}
