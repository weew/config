<?php

namespace Weew\Config;

use Weew\Config\Drivers\ArrayConfigDriver;

class ConfigLoader implements IConfigLoader {
    /**
     * @var array
     */
    protected $paths;

    /**
     * @var IConfigDriver[]
     */
    protected $drivers;

    /**
     * @param array $paths
     * @param IConfigDriver[]|null $drivers
     */
    public function __construct(array $paths = [], array $drivers = null) {
        if ($drivers === null) {
            $drivers = $this->createDefaultConfigDrivers();
        }

        $this->setPaths($paths);
        $this->setDrivers($drivers);
    }

    /**
     * @return Config
     */
    public function load() {
        $configs = [];

        foreach ($this->getPaths() as $path) {
            $configs[] = $this->loadPath($path);
        }

        return new Config($this->processConfiguration($configs));
    }

    /**
     * @return array
     */
    public function getPaths() {
        return $this->paths;
    }

    /**
     * @param array $paths
     */
    public function setPaths(array $paths) {
        $this->paths = $paths;
    }

    /**
     * @param $path
     */
    public function addPath($path) {
        $this->paths[] = $path;
    }

    /**
     * @param array $paths
     */
    public function addPaths(array $paths) {
        foreach ($paths as $path) {
            $this->addPath($path);
        }
    }

    /**
     * @return IConfigDriver[]
     */
    public function getDrivers() {
        return $this->drivers;
    }

    /**
     * @param IConfigDriver[] $drivers
     */
    public function setDrivers(array $drivers) {
        $this->drivers = $drivers;
    }

    /**
     * @param IConfigDriver $driver
     */
    public function addDriver(IConfigDriver $driver) {
        $this->drivers[] = $driver;
    }

    /**
     * @param array $drivers
     */
    public function addDrivers(array $drivers) {
        foreach ($drivers as $driver) {
            $this->addDriver($driver);
        }
    }

    /**
     * @param $path
     *
     * @return array
     */
    protected function loadPath($path) {
        if (directory_exists($path)) {
            return $this->loadDirectory($path);
        } else if (file_exists($path)) {
            return $this->loadFile($path);
        }

        return [];
    }

    /**
     * @param $path
     *
     * @return array
     */
    protected function loadFile($path) {
        $path = realpath($path);

        foreach ($this->getDrivers() as $driver) {
            if ($driver->supports($path)) {
                return $driver->loadFile($path);
            }
        }

        return [];
    }

    /**
     * @param $path
     *
     * @return array
     */
    protected function loadDirectory($path) {
        $configs = [];

        foreach (directory_list($path) as $directory) {
            $configs[] = $this->loadPath(
                path($path, $directory)
            );
        }

        return $this->processConfiguration($configs);
    }

    /**
     * @return array
     */
    protected function createDefaultConfigDrivers() {
        return [new ArrayConfigDriver()];
    }

    /**
     * @param array $configs
     *
     * @return array
     */
    protected function processConfiguration(array $configs) {
        $config = [];

        foreach ($configs as $group) {
            $config = array_extend_distinct($config, $group);
        }

        return $config;
    }
}
