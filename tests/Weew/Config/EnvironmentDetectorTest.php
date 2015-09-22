<?php

namespace Tests\Weew\Config;

use PHPUnit_Framework_TestCase;
use Weew\Config\EnvironmentDetector;

class EnvironmentDetectorTest extends PHPUnit_Framework_TestCase {
    public function test_detect_environment() {
        $detector = new EnvironmentDetector();
        $this->assertEquals('prod', $detector->detectEnvironment('foo/bar_prod.txt'));
        $this->assertEquals('prod', $detector->detectEnvironment('foo/bar_production.txt'));
        $this->assertEquals('test', $detector->detectEnvironment('foo/bar_test.txt'));
        $this->assertEquals('test', $detector->detectEnvironment('foo/bar_test.txt'));
        $this->assertEquals('dev', $detector->detectEnvironment('foo/bar_dev.txt'));
        $this->assertEquals('dev', $detector->detectEnvironment('foo/bar_dev.txt'));

        $this->assertNull($detector->detectEnvironment('foo/bar'));
    }

    public function test_add_rule() {
        $detector = new EnvironmentDetector();
        $this->assertNull($detector->detectEnvironment('foo/string_bar'));
        $this->assertNull($detector->detectEnvironment('foo/string_barrel'));

        $detector->addRule('bar', ['bar', 'barrel']);

        $this->assertEquals('bar', $detector->detectEnvironment('foo/string_bar'));
        $this->assertEquals('bar', $detector->detectEnvironment('foo/string_barrel'));
    }
}
