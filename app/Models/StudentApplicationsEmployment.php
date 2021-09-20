<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentApplicationsEmployment extends Model {

    use HasFactory;

    protected $table = 'student_applications_employment';
    
    protected $fillable = [
        'employer', 'job_title', 'full_or_part_time','employment_start_date','employment_end_date','role_description'
    ];


    /**
     * @param type $id
     * @return \App\Models\StudentApplicationsEmployment\Object|\App\Models\StudentApplicationsEmploymentCollection
     */
    public static function getStudentApplicationsEmploymentById($id) {

        $studentApplicationsEmploymentObject = StudentApplicationsEmployment::where('id', '=', $id)->first();
        if ($studentApplicationsEmploymentObject instanceof StudentApplicationsEmployment) {
            return $studentApplicationsEmploymentObject;
        } else {
            return new StudentApplicationsEmployment();
        }
    }

    /**
     * @param type $id
     * @return \App\Models\StudentApplicationsEmployment\Object|\App\Models\StudentApplicationsEmploymentCollection
     */
    public static function getStudentApplicationsEmploymentByIdCollection($id) {

        $studentApplicationsEmploymentObjectCollection = StudentApplicationsEmployment::where('student_applications_id', '=', $id)->get();
        if (!empty($studentApplicationsEmploymentObjectCollection)) {
            return $studentApplicationsEmploymentObjectCollection;
        }
    }
    
}
