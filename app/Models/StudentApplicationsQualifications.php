<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentApplicationsQualifications extends Model {

    use HasFactory;

    protected $table = 'student_applications_qualifications';

    /**
     * @param type $id
     * @return boolean|\App\Models\StudentApplicationsQualifications
     */
    public static function getStudentApplicationQualificationById($id) {

        $studentApplicationsQualifications = StudentApplicationsQualifications::where('id', '=', $id)->first();
        if ($studentApplicationsQualifications instanceof StudentApplicationsQualifications) {
            return $studentApplicationsQualifications;
        } else {
            return new StudentApplicationsQualifications();
        }
    }

    /**
     * @param type $id
     * @return boolean|\App\Models\StudentApplicationsQualifications
     */
    public static function getStudentApplicationQualificationByIdCollection($id) {

        $studentApplicationsQualifications = StudentApplicationsQualifications::where('student_applications_id', '=', $id)->get();
        return $studentApplicationsQualifications;
    }

    /**
     * @param type $id
     * @return boolean|\App\Models\StudentApplicationsQualifications
     */
    public static function getStudentApplicationQualificationByIdCollectionJoin($student_applications_id) {

        $nameofLabel = DB::table('student_applications_qualifications')->
                        leftjoin('hesa_student_alternative_qualtype', 'hesa_student_alternative_qualtype.hesa_code', '=', 'student_applications_qualifications.qualification')->
                        leftjoin('hesa_student_alternative_qualsbj', 'hesa_student_alternative_qualsbj.hesa_code', '=', 'student_applications_qualifications.subject')->
                        where('student_applications_id', '=', $student_applications_id)->get();

        return $nameofLabel;
    }

}

?>
