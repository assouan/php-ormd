<?php namespace ORMD\Cache;


/** @todo: create class Cache/Memcached */
class Memcached implements \ORMD\Cache\Cache
{
    function skeleton($pdo, $database_name, $database_id, $table_name)
    {
        return new \ORMD\Skeleton($pdo, $database_name, $table_name);
    }

    function clear()
    {
    }
}
