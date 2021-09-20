<?php

namespace App\Models\Hesa\Student;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mode extends Model
{
    //    use HasFactory;
    protected $table = 'hesa_student_mode';
    
      public static function getStudentMode() {
        return Mode::orderBy('hesa_label', 'ASC')->get();
    }
}

