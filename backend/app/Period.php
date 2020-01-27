<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    /**
     * Database connection override
     *
     * @var string
     */
    protected $connection = 'tenant';

    /**
     * Origin table name in DB
     *
     * @var string
     */
    protected $table = 'Periods2';

    /**
     * Get a period by its ordinal number
     *
     * @param integer $n
     * @return Period|null
     */
    public static function getByNumber(int $n): ?Period
    {
        return self::where('Period', $n)->first();
    }

    /**
     * Get a period's ordinal by its month and year
     *
     * @param string $month
     * @param string $year
     * @return integer|null
     */
    public static function getOrdinalByMonthYear(string $month, string $year): ?int
    {
        $p = self::where('Month', $month)->where('Year', $year)->first();
        return $p ? intval($p->Period) : null;
    }
}
