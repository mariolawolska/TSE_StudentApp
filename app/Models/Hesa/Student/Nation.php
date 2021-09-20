<?php

namespace App\Models\Hesa\Student;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nation extends Model {

    //    use HasFactory;
    protected $table = 'hesa_student_nation';

    public static function getStudentNation() {
        return Nation::orderBy('hesa_label', 'ASC')->get();
    }

}
