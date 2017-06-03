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

    /**
     * Set data as reference
     *
     * @param mixed $value
     */
    public function setDataReference(&$reference)
    {
        $this->data = &$reference;
    }

    /**
     * Separator getter
     *
     * @return string
     */
    public function getSeparator()
    {
        return $this->keySeparator;
    }

    /**
     * Value getter by key
     *
     * @param string $key
     *
     * @return mixed|null
     */
    public function get($key)
    {
        $result = null;
        $data = $this->getData();

        $path = $this->getPath($key);
        if ($lastLevelData = $this->getDeepLevelLink($data, $path) and is_array($lastLevelData)) {
            $mainKey = end($path);
            if (array_key_exists($mainKey, $lastLevelData)) {
                $result = $lastLevelData[$mainKey];
            }
        };

        return $result;
    }

    /**
     * Set the value under the key
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $data = &$this->data;

        $path = $this->getPath($key);
        $lastLevelData = &$this->ensureStructure($data, $path);
        $mainKey = end($path);
        $lastLevelData[$mainKey] = $value;
    }

    /**
     * Reset the value under the key
     *
     * @param string $key
     */
    public function reset($key)
    {
        $data = &$this->data;

        if (strpos($key, $this->getSeparator())) {
            $path = $this->getPath($key);
            $lastLevelData = &$this->getDeepLevelLink($data, $path);
            $mainKey = end($path);

            if (array_key_exists($mainKey, $lastLevelData)) {
                unset($lastLevelData[$mainKey]);
            }
        } elseif (isset($data[$key])) {
            unset($data[$key]);
        }
    }

    /**
     * Parses the key by separator
     *
     * @param string $key
     *
     * @return array
     */
    protected function getPath($key)
    {
        return explode($this->getSeparator(), $key);
    }

    /*
     * Move deep by key path and return deepest possible level data set Link
     *
     * @param mixed $data
     * @param array $path
     */
    protected function &getDeepLevelLink(&$data, $path)
    {
        $result = &$data;
        do {
            $step = array_shift($path);

            if (!isset($data[$step])) {
                $result = null;
                break;
            }

            if ($path) {
                $data = &$data[$step];
            } else {
                $result = &$data;
                break;
            }
        } while ($path);

        return $result;
    }

    /**
     * Ensure the path to exist in array
     *
     * @param array $data
     * @param array $path
     *
     * @return array link to the deepest level of path
     */
    protected function &ensureStructure(&$data, $path)
    {
        $result = &$data;
        do {
            $step = array_shift($path);
            if ($path) {
                if (!array_key_exists($step, $result)) {
                    $result[$step] = [];
                }

                $result = &$result[$step];
            }
        } while ($path);

        return $result;
    }
}
