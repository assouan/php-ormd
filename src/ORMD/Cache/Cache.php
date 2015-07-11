<?php namespace ORMD\Cache;

/**
 * Interface Cache
 */
interface Cache
{
    function skeleton($pdo, $database_name, $database_id, $table_name);
    function clear();
}
