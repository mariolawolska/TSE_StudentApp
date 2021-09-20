<?php

namespace App\Models\Hesa\StudentAlternative;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disable extends Model {

    // use HasFactory;
    protected $table = 'hesa_student_alternative_disable';

    public static function getStudentDisable() {
        return Disable::orderBy('hesa_label', 'ASC')->get();
    }

}
