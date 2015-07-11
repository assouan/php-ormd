<?php namespace ORMD;

/**
 * Class Link
 */
class Link
{
    /** @var string $database_id */
    protected $database_id;
    /** @var string $table_name */
    protected $table_name;
    /** @var \ORMD\Skeleton $target */
    protected $target;
    /** @var array $compares */
    protected $compares;

    /**
     * @param string $database_id
     * @param string $table_name
     * @param array $compares
     */
    function __construct($database_id, $table_name, array $compares)
    {
        $this->database_id = $database_id;
        $this->table_name  = $table_name;
        $this->target      = \ORMD::database($this->database_id)->invoke($this->table_name);
        $this->compares    = $compares;
    }

    /**
     * @return \ORMD\Skeleton
     */
    function target()
    {
        return $this->target;
    }

    /**
     * @return array
     */
    function compares()
    {
        return $this->compares;
    }
}