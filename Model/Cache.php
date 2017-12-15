<?php

namespace Model;

use Util\FormatUtil as FU;
use Util\MySQLi\MySQLiUtilPool as DB;

class Cache extends Model
{
    static function updateCache( array $args, array $set )
    {
        $set['update_time'] = FU::getMySQLDatetime();
        return self::updateInstance( $args, $set );
    }
}