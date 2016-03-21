<?php

namespace Weew\Config;

use Weew\Contracts\IArrayable;

interface IConfig extends IArrayable {
    /**
     * @return array
     */
    function getConfig();

    /**
     * @param array $config
     */
    function setConfig(array $config);

    /**
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    function get($key, $default = null);

    /**
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    function getRaw($key, $default = null);

    /**
     * @param $key
     * @param $value
     */
    function set($key, $value);

    /**
     * @param $key
     *
     * @return bool
     */
    function has($key);

    /**
     * @param $key
     */
    function remove($key);

    /**
     * @param array $config
     */
    function merge(array $config);

    /**
     * @param IConfig $config
     */
    function extend(IConfig $config);

    /**
     * @param $key
     * @param null $errorMessage
     * @param null $scalarType
     *
     * @return IConfig
     */
    function ensure($key, $errorMessage = null, $scalarType = null);

    /**
     * @param $key
     *
     * @return string
     */
    function getAbsoluteConfigKey($key);
}
