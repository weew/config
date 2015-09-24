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

    public function test_merge() {
        $config = new Config([
            'foo' => 'bar',
            'baz' => [
                'yolo' => 'swag',
                'items' => [1, 2, 3, 4],
            ],
        ]);
        $this->assertEquals([
            'foo' => 'bar',
            'baz' => [
                'yolo' => 'swag',
                'items' => [1, 2, 3, 4],
            ]
        ], $config->toArray());

        $config->merge([
            'foo' => 'swag',
            'baz' => [
                'foo' => 'bar',
                'items' => [9, 10],
            ],
        ]);
        $this->assertEquals([
            'foo' => 'swag',
            'baz' => [
                'yolo' => 'swag',
                'items' => [9, 10],
                'foo' => 'bar',
            ]
        ], $config->toArray());
    }

    public function test_extend() {
        $array1 = [
            'foo' => [
                'a' => 'b',
                'bar' => [1, 2, 3,]
            ]
        ];
        $array2 = [
            'foo' => [
                'x' => 'y',
                'bar' => [5],
            ],
        ];

        $config1 = new Config($array1);
        $config2 = new Config($array2);

        $config1->extend($config2);

        $this->assertEquals([
            'foo' => [
                'a' => 'b',
                'x' => 'y',
                'bar' => [5],
            ],
        ], $config1->toArray());
    }
}
