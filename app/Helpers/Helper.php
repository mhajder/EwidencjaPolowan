<?php

namespace App\Helpers;

use Carbon\Carbon;

/**
 * Class Helper
 * @package App\Helpers
 */
class Helper
{
    /**
     * MySQL datetime format string
     */
    const MYSQL_DATETIME_FORMAT = 'Y-m-d H:i:s';
    /**
     * Hunting Date Range Picker date format
     */
    const HUNTING_DATE_RANGE_PICKER_FORMAT = 'd/m/Y H:i';
    /**
     * Authorization Date Range Picker date format
     */
    const AUTHORIZATION_DATE_RANGE_PICKER_FORMAT = 'd/m/Y';

    /**
     * Get nearest time rounded up with minimum.
     *
     * @param Carbon $now
     * @param int $nearestMin
     * @param int $minimumMinutes
     * @return Carbon
     *
     * @from https://stackoverflow.com/a/56467805/13303385
     */
    public static function getNearestTimeRoundedUpWithMinimum(Carbon $now, $nearestMin = 15, $minimumMinutes = 1): Carbon
    {
        $nearestSec = $nearestMin * 60;
        $minimumMoment = $now->addMinutes($minimumMinutes);
        $futureTimestamp = ceil($minimumMoment->timestamp / $nearestSec) * $nearestSec;
        $futureMoment = Carbon::createFromTimestamp($futureTimestamp);
        return $futureMoment->startOfMinute();
    }

    /**
     * Check if authorization is valid.
     *
     * @param string $valid_from
     * @param string $valid_until
     * @return bool
     */
    public static function checkIfAuthorizationIsValid(string $valid_from, string $valid_until): bool
    {
        return Carbon::now()->between(Carbon::parse($valid_from), Carbon::parse($valid_until));
    }

    /**
     * Check if date is valid.
     *
     * @param string $date
     * @return bool
     */
    public static function checkIfDateIsValid(string $date): bool
    {
        return empty(strtotime($date)) ? false : true;
    }
}

