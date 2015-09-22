<?php

namespace Weew\Config;

class Config implements IConfig {
    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config = []) {
        $this->setConfig($config);
    }

    /**
     * @return array
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config) {
        $this->config = $config;
    }

    /**
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    public function get($key, $default = null) {
        return array_get($this->config, $key, $default);
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value) {
        array_set($this->config, $key, $value);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function has($key) {
        return array_has($this->config, $key);
    }

    /**
     * @param $key
     */
    public function remove($key) {
        array_remove($this->config, $key);
    }

    /**$
     * @return array
     */
    public function toArray() {
        return $this->config;
    }
}
