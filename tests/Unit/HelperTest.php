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
     *
     * @return void
     */
    public function testGetNearestTimeRoundedUpWithMinimum()
    {
        $this->assertEquals('2021-06-02 14:00:00', Helper::getNearestTimeRoundedUpWithMinimum(Carbon::parse('2021-06-01 14:12:59'), 60, 23 * 60 + 10)->format(Helper::MYSQL_DATETIME_FORMAT));
        $this->assertEquals('2021-06-01 14:15:00', Helper::getNearestTimeRoundedUpWithMinimum(Carbon::parse('2021-06-01 14:12:59'), 15, 1)->format(Helper::MYSQL_DATETIME_FORMAT));
        $this->assertEquals('2021-06-01 14:30:00', Helper::getNearestTimeRoundedUpWithMinimum(Carbon::parse('2021-06-01 14:12:59'), 30, 10)->format(Helper::MYSQL_DATETIME_FORMAT));
        $this->assertEquals('2021-06-01 16:00:00', Helper::getNearestTimeRoundedUpWithMinimum(Carbon::parse('2021-06-01 14:52:59'), 60, 50)->format(Helper::MYSQL_DATETIME_FORMAT));
        $this->assertEquals(Carbon::parse('tomorrow 15:00:00'), Helper::getNearestTimeRoundedUpWithMinimum(Carbon::parse('16:30'), 60, 60 * 22 + 30));
    }

    /**
     * Check if authorization is valid.
     *
     * @return void
     */
    public function testCheckIfAuthorizationIsValid()
    {
        $this->assertTrue(Helper::CheckIfAuthorizationIsValid('2020-01-01 00:00:00', '2050-01-01 00:00:00')); // Valid
        $this->assertFalse(Helper::CheckIfAuthorizationIsValid('2010-01-01 00:00:00', '2011-01-01 00:00:00')); // Not valid
    }

    /**
     * Check if date is valid.
     *
     * @return void
     */
    public function testCheckIfDateIsValid()
    {
        $this->assertTrue(Helper::CheckIfDateIsValid('2021-06-02 14:00:00'));
        $this->assertTrue(Helper::CheckIfDateIsValid('Tue Jun 01 2021 00:00:00 GMT+0200'));
        $this->assertTrue(Helper::CheckIfDateIsValid(-1));
        $this->assertFalse(Helper::CheckIfDateIsValid(0));
        $this->assertFalse(Helper::CheckIfDateIsValid(1));
        $this->assertFalse(Helper::CheckIfDateIsValid(null));
        $this->assertFalse(Helper::CheckIfDateIsValid('string not date'));
    }
}
