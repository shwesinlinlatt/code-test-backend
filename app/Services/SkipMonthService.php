<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class SkipMonthService
{
     static public function skipMonth($smokingStatus, $smokingInsideHome) {
        if ($smokingStatus == 'No' && $smokingInsideHome == 'No') {
          return true;
        } else if ($smokingStatus == 'Yes' && $smokingInsideHome == 'No') {

          return false;
        } else if ($smokingStatus == 'No' && $smokingInsideHome == 'Yes') {
          return false;
        } else {
          return false;
        };
      }
}
