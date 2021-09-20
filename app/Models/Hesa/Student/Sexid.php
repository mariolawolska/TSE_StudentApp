<?php

namespace App\Models\Hesa\Student;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sexid extends Model
{
    //    use HasFactory;
    protected $table = 'hesa_student_sexid';
    
    public static function getStudentSexid() {
        return Sexid::orderBy('hesa_label', 'ASC')->get();
    }
}
