<?php


namespace App\Utils;

class PatientUnique
{
    static public function generateUniqueID($code, $abbreviation, $year)
    {
        $unique_id = $code . '/' . $abbreviation . '/' . $year;

        return $unique_id;
    }
}
