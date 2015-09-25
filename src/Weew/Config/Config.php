<?php

namespace Weew\Config;

class Config implements IConfig {
    /**
     * @var array
     */
    protected $config;

    /**
     * @var IConfigParser
     */
    protected $parser;

    /**
     * @param array $config
     * @param IConfigParser $parser
     */
    public function __construct(array $config = [], IConfigParser $parser = null) {
        if ( ! $parser instanceof IConfigParser) {
            $parser = $this->createConfigParser();
        }

        $this->setConfigParser($parser);
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
        return $this->getConfigParser()
            ->parse($this, array_get($this->config, $key, $default));
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

    /**
     * @param array $config
     */
    public function merge(array $config) {
        $this->setConfig(
            array_extend_distinct($this->getConfig(), $config)
        );
    }

    /**
     * @param IConfig $config
     */
    public function extend(IConfig $config) {
        $this->merge($config->getConfig());
    }

    /**
     * @return array
     */
    public function toArray() {
        return $this->getConfigParser()
            ->parse($this, $this->getConfig());
    }

    /**
     * @return ConfigParser
     */
    protected function createConfigParser() {
        return new ConfigParser();
    }

    /**
     * @return IConfigParser
     */
    public function getConfigParser() {
        return $this->parser;
    }

    /**
     * @param IConfigParser $parser
     */
    public function setConfigParser(IConfigParser $parser) {
        $this->parser = $parser;
    }
}
