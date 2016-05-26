<?php

namespace Weew\Config;

interface IConfigLoader {
    /**
     * @param IConfig $config
     *
     * @return Config
     */
    function load(IConfig $config = null);

    /**
     * @return string
     */
    function getEnvironment();

    /**
     * @param $environment
     */
    function setEnvironment($environment);

    /**
     * @param string $name
     *
     * @return IConfigLoader
     */
    function addEnvironment($name);

    /**
     * @param array $names
     *
     * @return IConfigLoader
     */
    function addEnvironments(array $names);

    /**
     * @return IEnvironmentDetector
     */
    function getEnvironmentDetector();

    /**
     * @param IEnvironmentDetector $detector
     */
    function setEnvironmentDetector(IEnvironmentDetector $detector);

    /**
     * @return array
     */
    function getPaths();

    /**
     * @param array $paths
     */
    function setPaths(array $paths);

    /**
     * @param $path
     *
     * @return IConfigLoader
     */
    function addPath($path);

    /**
     * @param array $paths
     *
     * @return IConfigLoader
     */
    function addPaths(array $paths);

    /**
     * @return array
     */
    function getRuntimeConfigs();

    /**
     * @param array|IConfig $config
     *
     * @return IConfigLoader
     */
    function addRuntimeConfig($config);

    /**
     * Add a config source or extend currently loaded config with
     * new one, based on a config array, IConfig or a config path.
     *
     * @param array|string|IConfig $config
     *
     * @return IConfigLoader
     */
    function addConfig($config);

    /**
     * @return IConfigDriver[]
     */
    function getDrivers();

    /**
     * @param IConfigDriver[] $drivers
     */
    function setDrivers(array $drivers);

    /**
     * @param IConfigDriver $driver
     */
    function addDriver(IConfigDriver $driver);

    /**
     * @param array $drivers
     */
    function addDrivers(array $drivers);
}
