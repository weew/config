<?php

namespace Tests\Weew\Config;

use PHPUnit_Framework_TestCase;
use Weew\Config\ConfigLoader;
use Weew\Config\Drivers\ArrayConfigDriver;
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
        $loader = new ConfigLoader([], []);
        $loader->addPath(path(__DIR__, 'configs/good'));
        $config = $loader->load();
        $this->assertTrue(count($config->toArray()) == 0);
        $loader->addDrivers([new ArrayConfigDriver()]);
        $config = $loader->load();
        $this->assertTrue(count($config->toArray()) > 0);
    }

    public function test_add_paths() {
        $loader = new ConfigLoader(['foo']);
        $loader->addPath('bar');
        $loader->addPaths(['yolo', 'swag']);
        $this->assertEquals(['foo', 'bar', 'yolo', 'swag'], $loader->getPaths());
    }
}
