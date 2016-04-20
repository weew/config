<?php

namespace Tests\Weew\Config;

use PHPUnit_Framework_TestCase;
use Weew\Config\Config;
use Weew\Config\ConfigLoader;
use Weew\Config\Drivers\ArrayConfigDriver;
use Weew\Config\Exceptions\InvalidRuntimeConfigException;
use Weew\Config\IConfig;

class ConfigLoaderTest extends PHPUnit_Framework_TestCase {
    public function test_load_config() {
        $loader = new ConfigLoader();
        $loader->addPath(path(__DIR__, 'configs/good'));
        $config = $loader->load();

        $this->assertTrue($config instanceof IConfig);

        $expected = [
            'foo' => 'hashtag',
            'names' => ['Michael'],
            'yolo' => [
                'swag' => 'foobar',
                'nested' => ['list', 'of', 'values'],
                'additional' => ['key', 'value'],
            ],
            'key' => 'secret',
            'port' => 80,
            'james' => ['Bond', 'Hunt'],
        ];

        $this->assertEquals($expected, $config->toArray());
    }

    public function test_add_load_invalid_path() {
        $loader = new ConfigLoader();
        $loader->addPath('foo');
        $config = $loader->load();
        $this->assertTrue($config instanceof IConfig);
        $this->assertEquals([], $config->toArray());
    }

    public function test_load_unsupported_format() {
        $loader = new ConfigLoader();
        $loader->addPath(path(__DIR__, 'configs/unsupported.format'));
        $config = $loader->load();
        $this->assertTrue($config instanceof IConfig);
        $this->assertEquals([], $config->toArray());
    }

    public function test_add_drivers() {
        $loader = new ConfigLoader(null, [], []);
        $loader->addPath(path(__DIR__, 'configs/good'));
        $config = $loader->load();
        $this->assertTrue(count($config->toArray()) == 0);
        $loader->addDrivers([new ArrayConfigDriver()]);
        $config = $loader->load();
        $this->assertTrue(count($config->toArray()) > 0);
    }

    public function test_add_paths() {
        $loader = new ConfigLoader(null, ['foo']);
        $loader->addPath('bar');
        $loader->addPaths(['yolo', 'swag']);
        $this->assertEquals(['foo', 'bar', 'yolo', 'swag'], $loader->getPaths());
    }

    public function test_load_with_environment() {
        $loader = new ConfigLoader();
        $loader->addPath(path(__DIR__, 'configs/env'));
        $config = $loader->load()->toArray();

        $this->assertEquals(['foo' => 'dev', 'value' => 'dev', 'bar' => 'foo'], $config);

        $loader->setEnvironment('dev');
        $config = $loader->load()->toArray();
        $this->assertEquals(
            ['foo' => 'dev', 'value' => 'dev', 'bar' => 'foo'],
            $config
        );

        $loader->setEnvironment('test');
        $config = $loader->load()->toArray();
        $this->assertEquals(
            ['foo' => 'test', 'value' => 'test', 'bar' => 'foo'],
            $config
        );

        $loader->setEnvironment('prod');
        $config = $loader->load()->toArray();
        $this->assertEquals(
            ['foo' => 'prod', 'value' => 'prod', 'bar' => 'foo'],
            $config
        );
    }

    public function test_load_ini_files() {
        $loader = new ConfigLoader();
        $loader->addPath(path(__DIR__, 'configs/config.ini'));
        $config = $loader->load();

        $this->assertEquals([
            'foo' => 'bar',
            'bar' => 'foo',
            'section' => ['yolo' => 2],
        ], $config->toArray());
    }

    public function test_load_yaml_files() {
        $loader = new ConfigLoader();
        $loader->addPath(path(__DIR__, 'configs/config.yml'));
        $config = $loader->load();

        $this->assertEquals([
            'foo' => 'bar',
            'bar' => 'foo',
            'section' => ['yolo' => 'swag'],
        ], $config->toArray());
    }

    public function test_load_with_references() {
        $loader = new ConfigLoader();
        $loader->addPath(path(__DIR__, 'configs/references'));
        $config = $loader->load();

        $this->assertEquals([
            'list' => ['foo' => 'bar'],
            'nested' => ['list' => ['value' => 'bar']],
        ], $config->toArray());

        $this->assertEquals(
            ['list' => ['value' => 'bar']], $config->get('nested')
        );
    }

    public function test_get_and_set_runtime_configs() {
        $loader = new ConfigLoader();
        $this->assertEquals([], $loader->getRuntimeConfigs());
        $loader->addRuntimeConfig(['foo']);
        $loader->addRuntimeConfig(new Config(['bar']));
        $this->assertEquals([['foo'], ['bar']], $loader->getRuntimeConfigs());
    }

    public function test_invalid_runtime_config_exception_is_thrown() {
        $loader = new ConfigLoader();
        $this->setExpectedException(InvalidRuntimeConfigException::class);
        $loader->addRuntimeConfig('foo');
    }

    public function test_runtime_config_is_being_loaded_at_the_end() {
        $loader = new ConfigLoader();
        $loader->addPath(path(__DIR__, 'configs/references'));
        $loader->addRuntimeConfig(['list' => 'value']);
        $config = new Config(['some' => 'value']);
        $loader->addRuntimeConfig($config);
        $config = $loader->load();

        $this->assertEquals(
            [
                'nested' => ['list' => ['value' => null]],
                'list' => 'value',
                'some' => 'value',
            ],
            $config->toArray()
        );
    }

    public function test_add_config() {
        $loader = new ConfigLoader();
        $loader->addConfig(path(__DIR__, 'configs/config.ini'));
        $loader->addConfig([path(__DIR__, 'configs/references')]);
        $loader->addConfig(['key' => 'value']);
        $loader->addConfig(new Config(['some' => 'config']));
        $config = $loader->load();

        $this->assertEquals([
            'foo' => 'bar',
            'bar' => 'foo',
            'section' => [
                'yolo' => 2,
            ],
            'list' => [
                'foo' => 'bar',
            ],
            'nested' => [
                'list' => [
                    'value' => 'bar',
                ],
            ],
            'key' => 'value',
            'some' => 'config',
        ], $config->toArray());
    }
}
