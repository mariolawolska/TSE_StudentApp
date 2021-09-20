<?php

namespace App\Models\Hesa\Student;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genderid extends Model {

    //    use HasFactory;
    protected $table = 'hesa_student_genderid';

    public static function getStudentGenderid() {
        return Genderid::orderBy('hesa_label', 'ASC')->get();
    }

}
