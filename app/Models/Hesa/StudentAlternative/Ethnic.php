<?php

namespace App\Models\Hesa\StudentAlternative;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ethnic extends Model {

    // use HasFactory;
    protected $table = 'hesa_student_alternative_ethnic';

    public static function getStudentEthnic() {
        return Ethnic::orderBy('hesa_label', 'ASC')->get();
    }

}
