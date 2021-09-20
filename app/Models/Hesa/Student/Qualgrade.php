<?php

namespace App\Models\Hesa\Student;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualgrade extends Model
{
    // use HasFactory;
    protected $table = 'hesa_student_qualgrade';

    public function getHesaLabelAttribute() {
        return $this->hesa_code;
    }
}
