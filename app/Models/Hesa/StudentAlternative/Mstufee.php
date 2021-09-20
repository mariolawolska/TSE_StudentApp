<?php

namespace App\Models\Hesa\StudentAlternative;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mstufee extends Model
{
    // use HasFactory;
    protected $table = 'hesa_student_alternative_mstufee';
    
       public static function getStudentMstufee() {
        return Mstufee::orderBy('hesa_label', 'ASC')->get();
    }
}
