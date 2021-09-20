<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Hesa
{
    public static function renderXML($collection, $xml, $output = 'file')
    {
//        switch for debugging;
        $enable_errors = true; // default true;
//        $enable_errors = false; // default true;

        // error handling
        $hesa_staff_error = session('hesa_error');
        if ($hesa_staff_error && $enable_errors) {
            echo '<h1>HESA ERRORS FOUND:</h1>';

            $last_message = '';
            foreach (session('hesa_errors') AS $current_message) {
                //remove duplicates
                if ($current_message != $last_message) {
                    if (substr($current_message, 0, 4) == 'Hesa') {
                        //                    echo '<span style="color:blue;">' . $current_message . '</span><br>';
                        echo ''.$current_message.'<br>';
                    } else {
                        if (strtolower(substr($current_message, 0, 5)) == 'error') {
                            echo '<span style="color:red; margin-left: 10px;">'.$current_message.'</span><br>';
                        } else {
                            echo $current_message.'<br>';
                        }
                    }
                }
                $last_message = $current_message;
            }

            die();
        }

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>" . $xml;

        $filename = $collection->name . ' - ' . $collection->code . '.xml';

         if ($output == 'screen') {
             // screen
             return response($xml)->header("Content-type", "text/xml");
         } else {
             // file
            return response($xml)
            ->header('Content-Type', 'text/xml')
            ->header('Content-Description', 'File Transfer')
            ->header('Content-Transfer-Encoding', 'binary')
            ->header('Cache-Control', 'public')
            ->header('Content-Disposition', 'attachment; filename=' . $filename . '');
         }
    }

    public static function syncStudentApplicationsToVle() {
        // we need to get the country from student_application, but this table is on the Laravel database...
        // so the best solution I came up with was to freshly copy all applications from laravel to vle ( just usercourse_lookup_id & current_country )
        // then this allows us to join the data into our VLE query.
        //
        // fetch student_applications
        $student_applications = StudentApplications::select('user_course_lookup_id','current_country')->get()->toArray();

        if (count($student_applications) > 0) {
            // save data to vle
            $sql = '
            DROP TABLE IF EXISTS `tmp_student_applications`;
            CREATE TABLE `tmp_student_applications`  (
              `user_course_lookup_id` int NOT NULL,
              `iso_two_hesa` varchar(4) NULL, PRIMARY KEY (`user_course_lookup_id`));
            INSERT INTO tmp_student_applications(user_course_lookup_id, iso_two_hesa) VALUES ';
            $i = 0;
            foreach ($student_applications as $student_application) {
                $i++;
                if ($i > 1) {
                    $sql .= ',';
                }
                $sql .= '('.$student_application['user_course_lookup_id'].',"'.$student_application['current_country'].'")';
            }
            $sql .= ';';

            DB::connection('mysql_vle')->unprepared($sql);
        }

        return true;
    }
}