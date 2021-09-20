<?php

namespace App\Http\Controllers;

class HesaController extends Controller
{
    public function aggregate($collection)
    {
        $record = new \App\Models\Hesa\Aggregate\Record;
        // this gets loaded by default at the moment on construct

        // optional:
        $record->loadCollection ($collection);

        return $record->generateXml();
    }
    public function staff($collection)
    {
        $record = new \App\Models\Hesa\Staff\Record;
        // this gets loaded by default at the moment on construct

        // optional:
        $record->loadCollection ($collection);

        return $record->generateXml();
    }
    public function student($collection)
    {
        $record = new \App\Models\Hesa\Student\Record;
        // this gets loaded by default at the moment on construct

        // optional:
        $record->loadCollection ($collection);

        return $record->generateXml();
    }
    public function studentalternative($collection)
    {
        $record = new \App\Models\Hesa\StudentAlternative\Record;
        // this gets loaded by default at the moment on construct

        // optional:
        $record->loadCollection ($collection);

        return $record->generateXml();
    }
}
