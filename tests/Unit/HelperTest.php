<?php

namespace Tests\Unit;

use App\Helpers\Helper;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

/**
 * Class HelperTest
 * @package Tests\Unit
 */
class HelperTest extends TestCase
{

    /**
     * Get nearest time rounded up with minimum.
     * nearestMin = 60
     * minimumMin = 1390
     *
     * @return void
     */
    public function testGetNearestTimeRoundedUpNearestMin_60MinimumMin_1390() {
        $this->assertEquals('2021-06-02 14:00:00', Helper::getNearestTimeRoundedUpWithMinimum(Carbon::parse('2021-06-01 14:12:59'), 60, 23 * 60 + 10)->format(Helper::MYSQL_DATETIME_FORMAT));
    }

    /**
     * Get nearest time rounded up with minimum.
     * nearestMin = 15
     * minimumMin = 1
     *
     * @return void
     */
    public function testGetNearestTimeRoundedUpNearestMin_15MinimumMin_1() {
        $this->assertEquals('2021-06-01 14:15:00', Helper::getNearestTimeRoundedUpWithMinimum(Carbon::parse('2021-06-01 14:12:59'), 15, 1)->format(Helper::MYSQL_DATETIME_FORMAT));
    }

    /**
     * Get nearest time rounded up with minimum.
     * nearestMin = 30
     * minimumMin = 10
     *
     * @return void
     */
    public function testGetNearestTimeRoundedUpNearestMin_30MinimumMin_10() {
        $this->assertEquals('2021-06-01 14:30:00', Helper::getNearestTimeRoundedUpWithMinimum(Carbon::parse('2021-06-01 14:12:59'), 30, 10)->format(Helper::MYSQL_DATETIME_FORMAT));
    }

    /**
     * Get nearest time rounded up with minimum.
     * nearestMin = 60
     * minimumMin = 50
     *
     * @return void
     */
    public function testGetNearestTimeRoundedUpNearestMin_60MinimumMin_50() {
        $this->assertEquals('2021-06-01 16:00:00', Helper::getNearestTimeRoundedUpWithMinimum(Carbon::parse('2021-06-01 14:52:59'), 60, 50)->format(Helper::MYSQL_DATETIME_FORMAT));
    }

    /**
     * Get nearest time rounded up with minimum.
     * nearestMin = 60
     * minimumMin = 1350
     *
     * @return void
     */
    public function testGetNearestTimeRoundedUpNearestMin_60MinimumMin_1350Tomorrow() {
        $this->assertEquals(Carbon::parse('tomorrow 15:00:00'), Helper::getNearestTimeRoundedUpWithMinimum(Carbon::parse('16:30'), 60, 60 * 22 + 30));
    }

    /**
     * Check if authorization is valid.
     * is valid = true
     *
     * @return void
     */
    public function testCheckIfAuthorizationIsValidTrue() {
        $this->assertTrue(Helper::CheckIfAuthorizationIsValid('2020-01-01 00:00:00', '2050-01-01 00:00:00'));
    }

    /**
     * Check if authorization is valid.
     * is not valid = false
     *
     * @return void
     */
    public function testCheckIfAuthorizationIsValidFalse() {
        $this->assertFalse(Helper::CheckIfAuthorizationIsValid('2010-01-01 00:00:00', '2011-01-01 00:00:00'));
    }
}
