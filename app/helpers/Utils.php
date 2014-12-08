<?php

class Utils {
    public static function calcDates($fromdate, $todate) {
        if ($todate == '' && $fromdate == '') {
            $fromdate = \Carbon\Carbon::now()->startOfDay();
            $todate = \Carbon\Carbon::now()->endOfDay()->addHours(10);
        } else if($todate == '') {
            $todate = $fromdate;
        } else {
            $arr = explode('-', $fromdate);
            $arr1 = explode('-', $todate);
            $fromdate = \Carbon\Carbon::createFromDate($arr[0], $arr[1], $arr[2])->startOfDay();
            $todate = \Carbon\Carbon::createFromDate($arr1[0], $arr1[1], $arr1[2])->endOfDay();
        }
        return [$fromdate, $todate];
    }
} 