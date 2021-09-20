<?php

namespace App\Models\Thinkspace_Vle;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ThinkspaceVle extends Model {

    /**
     * Method gets getNameOfCourse
     * 
     * @return array
     */
    public static function getNameOfCourse() {

        // SELECT * FROM thinkspace_vle.course_group where deleted = 0 AND (is_ma = 1 OR is_mfa = 1);

        return DB::connection('mysql_vle')
                        ->table('course_group as cg')
                        ->select('cg.*')
                        ->where('cg.deleted', '=', 0)
                        ->where(
                                function($query) {
                            return $query
                                    ->where('is_ma', '=', 1)
                                    ->orWhere('is_mfa', '=', 1);
                        })
                        ->orderBy('cg.course_group_id')
                        ->get();
    }
    
    /**
     * Methods gets TitleOfCourse
     * 
     * @return array
     */
    public static function getTitleOfCourse($course_id) {

        return DB::connection('mysql_vle')
                        ->table('course_group as cg')
                        ->select('cg.course_title')
                        ->where('cg.course_group_id', '=', $course_id)
                        ->first();
    }

    /**
     * Methods gets ModeOfStudyTypeMa
     * 
     * @return array
     */
    public static function getModeOfStudyTypeMa() {

        // SELECT * FROM thinkspace_vle.course_group where deleted = 0 AND is_ma = 1 OR is_ma = 1;
        return DB::connection('mysql_vle')
                        ->table('course_group as cg')
                        ->select('cg.*')
                        ->where('cg.deleted', '=', 0)
                        ->where(
                                function($query) {
                            return $query
                                    ->where('is_ma', '=', 1);
                        })
                        ->orderBy('cg.course_group_id')
                        ->get();
    }

    /**
     * Methods gets ModeOfStudyTypeMaIdArray
     * @param type $modeOfStudyMA
     * 
     * @return array
     */
    public static function getModeOfStudyTypeMaIdArray($modeOfStudyMA): array {

        $maIdArray = [];
        foreach ($modeOfStudyMA as $mode) {
            $maIdArray[$mode->course_group_id] = $mode->course_group_id;
        }
        return $maIdArray;
    }

    /**
     * Methods gets ModeOfStudyTypeMfa
     * 
     * @return type array
     */
    public static function getModeOfStudyTypeMfa() {

        //SELECT * FROM thinkspace_vle.course_group where deleted = 0 AND is_ma = 1 OR is_mfa = 1;
        return DB::connection('mysql_vle')
                        ->table('course_group as cg')
                        ->select('cg.*')
                        ->where('cg.deleted', '=', 0)
                        ->where(
                                function($query) {
                            return $query
                                    ->where('is_mfa', '=', 1);
                        })
                        ->orderBy('cg.course_group_id')
                        ->get();
    }

    /**
     * Methods gets ModeOfStudyTypeMfaIdArray
     * @param type $modeOfStudyMFA
     * 
     * @return array
     */
    public static function getModeOfStudyTypeMfaIdArray($modeOfStudyMFA): array {
        $mfaIdArray = [];
        foreach ($modeOfStudyMFA as $mode) {
            $mfaIdArray[$mode->course_group_id] = $mode->course_group_id;
        }
        return $mfaIdArray;
    }

    public static function getStudentRecord(int $studentId) {

        return $courseGroupArray = DB::connection('mysql_vle')
                ->table('user as us')
                ->select('us.*', 'student_record.*')
                ->leftJoin('student_record', 'student_record.user_id', '=', 'us.user_id')
                ->where('us.user_id', '=', $studentId)
                ->orderBy('us.user_id')
                ->get();
    }

    /**
     * Methods gets StudentCourse
     * @param int $studentId
     * 
     * @return array
     */
    public static function getStudentCourse(int $studentId) {

        return $courseGroupArray = DB::connection('mysql_vle')
                ->table('user_course_lookup as ucl')
                ->select('ucl.*', 'course.*', 'course_group.*')
                ->leftJoin('course', 'course.course_id', '=', 'ucl.course_id')
                ->leftJoin('course_group', 'course_group.course_group_id', '=', 'course.course_group_id')
                ->where('ucl.user_id', '=', $studentId)
                ->orderBy('ucl.user_id')
                ->get();
    }
}
