<?php namespace ORMD;

/**
 * Class Variant
 */
class Variant
{
    /** @var int $type */
    protected $type = 0;
    /** @var mixed $value */
    protected $value = null;

    const Invalid = 0;
    const Query = 1;
    const Is_Null = 2;
    const Is_Not_Null = 3;

    /**
     * @param int $type
     * @param mixed $value
     */
    public function __construct($type, $value = null)
    {
        $this->set($type, $value);
    }

    /**
     * @param int $type
     * @param mixed $value
     */
    public function set($type, $value = null)
    {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        switch ($this->type)
        {
            case self::Invalid: return 'Invalid'; break;
            default: return 'Unknown'; break;
        }
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if ($this->type != self::Invalid)
            return true;

        return false;
    }

    /**
     * @return string
     */
    public function toSql()
    {
        switch ($this->type)
        {
            case self::Invalid: return 'Invalid'; break;
            //default: return 'Unknown'; break;
        }

        return '';
    }
}
