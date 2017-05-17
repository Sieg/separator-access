<?php

namespace Sieg\SeparatorAccess;

class DataContainer
{
    /**
     * Local database for get/set
     * @var array
     */
    protected $data = [];

    /**
     * Separator to explode search key
     * @var string
     */
    protected $keySeparator = '.';

    /**
     * DataContainer constructor.
     *
     * @param array|null
     */
    public function __construct($data = null)
    {
        if (is_array($data)) {
            $this->setData($data);
        }
    }

    /**
     * Data getter
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set data
     *
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}