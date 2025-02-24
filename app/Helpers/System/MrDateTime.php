<?php

namespace App\Helpers\System;

use App\Exceptions\Handler;
use Carbon\Carbon;
use DateTimeZone;
use Symfony\Component\HttpFoundation\Response;

class MrDateTime extends \DateTime
{
    const MYSQL = 'Y-m-d H:i:s.u';
    const MYSQL_DATE = 'Y-m-d';
    const MYSQL_DATETIME = 'Y-m-d H:i:s';
    const SHORT_DATE = 'd.m.Y';
    const FULL_TIME = 'H:i:s';
    const SHORT_TIME = 'H:i';

    const SHORT_DATE_FULL_TIME = 'd.m.Y H:i:s';
    const SHORT_DATE_SHORT_TIME = 'd.m.Y H:i';

    const AMERICAN_DATE_TIME = 'm/d/Y H:i:s';

    const MYSQL_DATE_TIMEZONE = 'Y-m-d H:i:s.u P';

    const MYSQL_DATE_TIMEZONE_SHORT = 'Y-m-d H:i:s P';

    const MYSQL_DATE_TIMEZONE_SHORT_DATE = 'Y-m-d H:i P';

    const SHORT_TIME_SHORT_DATE = 'H:i d.m.Y';

    public function __construct($time = 'now', DateTimeZone $timezone = null)
    {
        if (is_integer($time)) {
            $time = '@' . $time;
        } elseif ($time instanceof \DateTime) {
            $time = $time->format(self::MYSQL_DATETIME);
        }

        if (!$timezone) {
            $timezone = self::getDefaultTimezone();
        }

        parent::__construct($time, $timezone);
    }

    private static $default_timezone = -1;

    public static function getDefaultTimezone()
    {
        if (self::$default_timezone === -1) {
            self::$default_timezone = new \DateTimeZone(env('timezone', 'Europe/Moscow'));
        }

        return self::$default_timezone;
    }

    public static function GetFromToDate(?MrDateTime $from, ?MrDateTime $to, $without_word = false): string
    {
        $r = '';

        if ($from && $to) {
            if ($from->diff($to)->days === 0) {
                return $from->format('d.m.Y');
            }
        }

        if ($from) {
            if ($without_word) {
                $r .= __('mr-t.с');
            }

            $r .= ' ' . $from->format('d.m.Y');
        }

        if ($to) {
            if (strlen($r)) {
                $r .= ' - ';
            }

            if ($without_word) {
                $r .= __('mr-t.по');
            }

            $r .= ' ' . $to->format('d.m.Y');
        }

        return $r;
    }

    private static $now = null;

    public static function now(): MrDateTime
    {
        if (!self::$now) {
            self::$now = new MrDateTime();
        }

        return self::$now;
    }

    public static function fromValue($value, ?string $format = null)
    {
        abort_if(!$value, Response::HTTP_INTERNAL_SERVER_ERROR, Handler::CODE_3001);
        if (!$value instanceof MrDateTime) {
            $value = new MrDateTime($value);
        }

        return $value;
    }


    public function isBefore(MrDateTime $datetime, bool $allow_equal = false): bool
    {
        $d1 = $this->getMysqlDateTime();
        $d2 = $datetime->getMysqlDateTime();

        if ($d1 === $d2 && $allow_equal) {
            return true;
        } else {
            return $d1 < $d2;
        }
    }

    public function isAfter(MrDateTime $datetime, bool $allow_equal = false): bool
    {
        $d1 = $this->getMysqlDateTime();
        $d2 = $datetime->getMysqlDateTime();

        if ($d1 === $d2 && $allow_equal) {
            return true;
        } else {
            return $d1 > $d2;
        }
    }

    public function getShortDate(): string
    {
        return $this->format(MrDateTime::SHORT_DATE);
    }

    private $full_time = -1;

    public function getFullTime(): string
    {
        if ($this->full_time === -1) {
            $this->full_time = $this->format(MrDateTime::FULL_TIME);
        }

        return $this->full_time;
    }

    private $short_time = -1;

    public function getShortTime(): string
    {
        if ($this->short_time === -1) {
            $this->short_time = $this->format(MrDateTime::SHORT_TIME);
        }

        return $this->short_time;
    }

    public function getShortDateFullTime(): string
    {
        return $this->getShortDate() . ' ' . $this->getFullTime();
    }

    public function getShortDateShortTime(): string
    {
        return $this->getShortDate() . ' ' . $this->getShortTime();
    }

    private $mysql_date = -1;

    public function getMysqlDate(): string
    {
        if ($this->mysql_date === -1) {
            $this->mysql_date = $this->format(MrDateTime::MYSQL_DATE);
        }

        return $this->mysql_date;
    }

    private $mysql_datetime = -1;

    public function getMysqlDateTime(): string
    {
        if ($this->mysql_datetime === -1) {
            $this->mysql_datetime = $this->format(MrDateTime::MYSQL_DATETIME);
        }

        return $this->mysql_datetime;
    }

    public function getShortDateTitleShortTime(): string
    {
        $r = "";
        $r .= "<span title='" . $this->getFullTime() . "'>{$this->getShortDate()}</span>";

        return $r;
    }

    #region Research
    private static $time_spent = null;

    public static function Start()
    {
        self::$time_spent = microtime(true);
    }

    public static $spent_result = array();

    public static function StopItem(string $note = null)
    {
        if ($note) {
            self::$spent_result[$note] = sprintf('%.4f sec', microtime(true) - self::$time_spent);
        } else {
            self::$spent_result[] = sprintf('%.4f sec', microtime(true) - self::$time_spent);
        }
    }

    public static function getTimeResult(): array
    {
        return self::$spent_result;
    }

    #endregion


    public function AddDays(int $number_of_days = 1): MrDateTime
    {
        $new_date = clone $this;

        if (!$number_of_days) {
            return $new_date;
        }

        $str = ($number_of_days >= 0 ? '+' : '') . $number_of_days . ' days';

        return new self($new_date->modify($str));
    }

    public function AddSeconds(int $number_of_seconds = 1): MrDateTime
    {
        $new_date = clone $this;

        if (!$number_of_seconds) {
            return $new_date;
        }

        $str = ($number_of_seconds >= 0 ? '+' : '') . $number_of_seconds . ' seconds';

        return new self($new_date->modify($str));
    }

    public function AddHours(int $number_of_hours = 1): MrDateTime
    {
        $new_date = clone $this;

        if (!$number_of_hours) {
            return $new_date;
        }

        $str = ($number_of_hours >= 0 ? '+' : '') . $number_of_hours . ' hours';

        return new self($new_date->modify($str));
    }

    public function AddMinutes(int $number_of_minutes = 1): MrDateTime
    {
        $new_date = clone $this;

        if (!$number_of_minutes) {
            return $new_date;
        }

        $str = ($number_of_minutes >= 0 ? '+' : '') . $number_of_minutes . ' minutes';

        return new self($new_date->modify($str));
    }

    public function AddMonths(int $number_of_months = 1): MrDateTime
    {
        $str = ($number_of_months >= 0 ? '+' : '') . $number_of_months . ' month';

        $new_date = clone $this;
        if ($new_date->modify($str) === false) {
            dd('$new_date->modify error');
        }

        if ($new_date->format('j') != $this->format('j')) //we exceeded month limits
        {
            $new_date->modify('last day of last month');
        }

        return new self($new_date);
    }

    public function AddYears(int $number_of_years = 1): MrDateTime
    {
        $str = ($number_of_years >= 0 ? '+' : '') . $number_of_years . ' years';

        $new_date = clone $this;
        return new self($new_date->modify($str));
    }

    public static function toDateTime(Carbon $obj): string
    {
        return $obj->format(self::AMERICAN_DATE_TIME);
    }
}
