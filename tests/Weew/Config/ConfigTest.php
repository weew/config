<?php

namespace Tests\Weew\Config;

use PHPUnit_Framework_TestCase;
use Weew\Config\Config;

class ConfigTest extends PHPUnit_Framework_TestCase {
    public function test_get_and_set_config() {
        $config = new Config(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $config->getConfig());
        $config->setConfig(['yolo' => 'swag']);
        $this->assertEquals(['yolo' => 'swag'], $config->getConfig());
    }

    public function test_to_array() {
        $config = new Config(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $config->toArray());
    }

    public function test_getters_and_setters() {
        $config = new Config([
            'foo' => 'bar',
            'yolo' => [
                'swag' => 'baz'
            ],
        ]);
        $this->assertEquals('bar', $config->get('foo'));
        $this->assertNull($config->get('bar'));
        $this->assertEquals('bar', $config->get('bar', 'bar'));
        $this->assertEquals('baz', $config->get('yolo.swag'));
        $config->set('a.nested.value', 'foo');
        $this->assertEquals('foo', $config->get('a.nested.value'));
        $this->assertFalse($config->has('baz'));
        $this->assertTrue($config->has('yolo.swag'));
        $config->remove('yolo.swag');
        $this->assertFalse($config->has('yolo.swag'));
    }
}
