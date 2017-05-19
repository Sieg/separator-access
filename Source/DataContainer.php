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
        $data = $this->getData();
        $result = null;

        if (strpos($key, $this->getSeparator())) {
            $path = $this->getPath($key);
            if ($lastLevelData = $this->getDeepLevelLink($data, $path) and is_array($lastLevelData)) {
                $mainKey = end($path);
                if (array_key_exists($mainKey, $lastLevelData)) {
                    $result = $lastLevelData[$mainKey];
                }
            };
        } elseif (isset($data[$key])) {
            $result = $data[$key];
        }

        return $result;
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
     */
    protected function &getDeepLevelLink(&$data, $path)
    {
        $result = &$data;
        do {
            if (!is_array($result)) {
                $result = null;
                break;
            }

            $step = array_shift($path);
            if ($path) {
                if (!isset($data[$step])) {
                    $result = null;
                    break;
                }

                $data = &$data[$step];
            } else {
                $result = &$data;
                break;
            }
        } while ($path);

        return $result;
    }
}