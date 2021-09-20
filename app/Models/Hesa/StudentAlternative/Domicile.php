<?php

namespace App\Models\Hesa\StudentAlternative;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domicile extends Model {

    // use HasFactory;
    protected $table = 'hesa_student_alternative_domicile';

    public static function getStudentDomicile() {
        return Domicile::orderBy('hesa_label', 'ASC')->get();
    }

}
