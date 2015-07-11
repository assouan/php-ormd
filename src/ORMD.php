<?php

/**
 * Class ORMD
 * @api
 */
class ORMD
{
    // Databases ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    /** @var \ORMD[] $databases */
    static protected $databases = array();

    /**
     * @param \PDO $pdo
     * @param string $database_name
     * @param string $database_id
     * @return \ORMD
     */
    static function addDatabase($pdo, $database_name, $database_id)
    {
        return self::$databases[$database_id] = new ORMD($pdo, $database_name, $database_id);
    }

    /**
     * @static
     * @param string $database_id
     * @return \ORMD|null
     */
    static function database($database_id)
    {
        if ( isset(self::$databases[$database_id]) || array_key_exists($database_id, self::$databases) )
            return self::$databases[$database_id];
        return null;
    }

    // Cache ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    /** @var \ORMD\Cache\Cache|null $cache_handler if null, cache disabled*/
    static protected $cache_handler = null;

    /**
     * @param string $driver Invalid driver name, disable the cache
     * @param ...$params
     */
    static function setCache($driver = '', ...$params)
    {
        switch ($driver)
        {
            // Memcached
            case 'memcached': { self::$cache_handler = new \ORMD\Cache\Memcached(...$params); } break;

            // Default (disable the cache)
            default: { self::$cache_handler = null; } break;
        }
    }

    static function clearCache()
    {
        if (self::$cache_handler)
            self::$cache_handler->clear();
    }

    // Object ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    /** @var \PDO $pdo */
    protected $pdo;
    /** @var string $database_name */
    protected $database_name;
    /** @var string $database_id */
    protected $database_id;

    /** @var \ORMD\Skeleton[] $cache */
    protected $cache = array();

    /**
     * @param \PDO $pdo
     * @param string $database_name
     * @param string|null $database_id if null $database_id = $database_name
     */
    public function __construct($pdo, $database_name, $database_id = null)
    {
        $this->pdo = $pdo;
        $this->database_name = $database_name; // (empty($database)) ? $this->pdo->query('select database()')->fetchColumn() : $database;

        $database_id = zzd('default', $database_id, $database_name);
        $this->database_id = $database_id;
    }

    /**
     * Return an ORM of the table name
     * @param string $table_name
     * @return \ORMD\Skeleton
     */
    public function invoke($table_name)
    {
        if ( isset($this->cache[$table_name]) )
            return $this->cache[$table_name];
        elseif ( self::$cache_handler )
            return $this->cache[$table_name] = self::$cache_handler->skeleton($this->pdo, $this->database_name, $this->database_id, $table_name);
        else
            return $this->cache[$table_name] = new \ORMD\Skeleton($this->pdo, $this->database_name, $table_name);
    }

    /**
     * Return an ORM of the table name
     * @param string $table_name
     * @return \ORMD\Skeleton
     */
    public function __invoke($table_name)
    {
        return $this->invoke($table_name);
    }

    /**
     * for '.' use __DOT__
     * for '-' use __DASH__
     * for '@' use __AT__
     * for '#' use __SHARP__
     * @param string $table_name
     * @return string
     */
    private function convertTableName($table_name)
    {
        return str_replace(
            array('__DOT__', '__DASH__', '__AT__', '__SHARP__'),
            array('.', '-', '@', '#'),
            $table_name
        );
    }

    /**
     * Return an ORM of the table name
     * @param string $table_name Table name will be converted
     * @return \ORMD\Skeleton
     */
    public function __get($table_name)
    {
        return $this->invoke($this->convertTableName($table_name));
    }

    // Special

    /**
     * @param string $database_id
     * @param string $table_name
     * @param array $compares
     * @return array
     */
    static function link($database_id, $table_name, array $compares, $multi = false)
    {
        return [
            'database_id' => $database_id,
            'table_name' => $table_name,
            'compares' => $compares,
            'multi' => $multi
        ];
        //return new ORMD\Link($database_id, $table_name, $compares);
    }

    // Special SQL
}
