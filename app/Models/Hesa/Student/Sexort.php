<?php

namespace App\Models\Hesa\Student;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sexort extends Model {

    //    use HasFactory;
    protected $table = 'hesa_student_sexort';

    public static function getStudentSexort() {
        return Sexort::orderBy('hesa_label', 'ASC')->get();
    }

}
