<?php
    namespace App\Service;

    class DateCalculator
    {
        public function yearDifference($year){
            $curYear = date('Y');
            $diff = $curYear - $year;
            return $diff;
        }
    }

?>