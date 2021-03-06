<?php

namespace Tests\Weew\Config;

use PHPUnit_Framework_TestCase;
use Weew\Config\EnvironmentDetector;

class EnvironmentDetectorTest extends PHPUnit_Framework_TestCase {
    public function test_default_environments() {
        $detector = new EnvironmentDetector();

        $this->assertEquals('prod', $detector->detectEnvironment('prod'));
        $this->assertEquals('prod', $detector->detectEnvironment('_prod'));
        $this->assertEquals('prod', $detector->detectEnvironment('_prod_'));
        $this->assertEquals('prod', $detector->detectEnvironment('_prod.php'));
        $this->assertEquals('prod', $detector->detectEnvironment('foo_prod'));
        $this->assertEquals('prod', $detector->detectEnvironment('foo_prod.php'));
        $this->assertNull($detector->detectEnvironment('prodfoo'));
        $this->assertNull($detector->detectEnvironment('fooprod'));
        $this->assertNull($detector->detectEnvironment('_prodfoo'));

        $this->assertEquals('prod', $detector->detectEnvironment('production'));
        $this->assertEquals('prod', $detector->detectEnvironment('_production'));
        $this->assertEquals('prod', $detector->detectEnvironment('_production_'));
        $this->assertEquals('prod', $detector->detectEnvironment('_production.php'));
        $this->assertEquals('prod', $detector->detectEnvironment('foo_production'));
        $this->assertEquals('prod', $detector->detectEnvironment('foo_production.php'));
        $this->assertNull($detector->detectEnvironment('productionfoo'));
        $this->assertNull($detector->detectEnvironment('fooproduction'));
        $this->assertNull($detector->detectEnvironment('_productionfoo'));

        $this->assertEquals('dev', $detector->detectEnvironment('dev'));
        $this->assertEquals('dev', $detector->detectEnvironment('_dev'));
        $this->assertEquals('dev', $detector->detectEnvironment('_dev_'));
        $this->assertEquals('dev', $detector->detectEnvironment('_dev.php'));
        $this->assertEquals('dev', $detector->detectEnvironment('foo_dev'));
        $this->assertEquals('dev', $detector->detectEnvironment('foo_dev.php'));
        $this->assertNull($detector->detectEnvironment('devfoo'));
        $this->assertNull($detector->detectEnvironment('foodev'));
        $this->assertNull($detector->detectEnvironment('_devfoo'));

        $this->assertEquals('dev', $detector->detectEnvironment('development'));
        $this->assertEquals('dev', $detector->detectEnvironment('_development'));
        $this->assertEquals('dev', $detector->detectEnvironment('_development_'));
        $this->assertEquals('dev', $detector->detectEnvironment('_development.php'));
        $this->assertEquals('dev', $detector->detectEnvironment('foo_development'));
        $this->assertEquals('dev', $detector->detectEnvironment('foo_development.php'));
        $this->assertNull($detector->detectEnvironment('developmentfoo'));
        $this->assertNull($detector->detectEnvironment('foodevelopment'));
        $this->assertNull($detector->detectEnvironment('_developmentfoo'));

        $this->assertEquals('test', $detector->detectEnvironment('test'));
        $this->assertEquals('test', $detector->detectEnvironment('_test'));
        $this->assertEquals('test', $detector->detectEnvironment('_test_'));
        $this->assertEquals('test', $detector->detectEnvironment('_test.php'));
        $this->assertEquals('test', $detector->detectEnvironment('foo_test'));
        $this->assertEquals('test', $detector->detectEnvironment('foo_test.php'));
        $this->assertNull($detector->detectEnvironment('testfoo'));
        $this->assertNull($detector->detectEnvironment('footest'));
        $this->assertNull($detector->detectEnvironment('_testfoo'));

        $this->assertEquals('stage', $detector->detectEnvironment('stage'));
        $this->assertEquals('stage', $detector->detectEnvironment('_stage'));
        $this->assertEquals('stage', $detector->detectEnvironment('_stage_'));
        $this->assertEquals('stage', $detector->detectEnvironment('_stage.php'));
        $this->assertEquals('stage', $detector->detectEnvironment('foo_stage'));
        $this->assertEquals('stage', $detector->detectEnvironment('foo_stage.php'));
        $this->assertNull($detector->detectEnvironment('stagefoo'));
        $this->assertNull($detector->detectEnvironment('foostage'));
        $this->assertNull($detector->detectEnvironment('_stagefoo'));

        $this->assertNull($detector->detectEnvironment('foo/bar'));

        $this->assertFalse($detector->detectEnvironment('dist.'));
        $this->assertFalse($detector->detectEnvironment('dist_'));
        $this->assertFalse($detector->detectEnvironment('dist-'));

        $this->assertFalse($detector->detectEnvironment('.dist'));
        $this->assertFalse($detector->detectEnvironment('_dist'));
        $this->assertFalse($detector->detectEnvironment('-dist'));

        $this->assertFalse($detector->detectEnvironment('dist.foo'));
        $this->assertFalse($detector->detectEnvironment('dist_foo'));
        $this->assertFalse($detector->detectEnvironment('dist-foo'));

        $this->assertFalse($detector->detectEnvironment('foo.dist'));
        $this->assertFalse($detector->detectEnvironment('foo_dist'));
        $this->assertFalse($detector->detectEnvironment('foo-dist'));

        $this->assertFalse($detector->detectEnvironment('foo.dist.bar'));
        $this->assertFalse($detector->detectEnvironment('foo_dist_bar'));
        $this->assertFalse($detector->detectEnvironment('foo-dist-bar'));

        $this->assertFalse($detector->detectEnvironment('foo-dist.bar'));
        $this->assertFalse($detector->detectEnvironment('foo_dist-bar'));
        $this->assertFalse($detector->detectEnvironment('foo.dist:bar'));
        $this->assertFalse($detector->detectEnvironment('foo+dist:bar'));

        $this->assertFalse($detector->detectEnvironment('dist'));
    }

    public function test_add_rule() {
        $detector = new EnvironmentDetector();
        $this->assertNull($detector->detectEnvironment('bar'));
        $this->assertNull($detector->detectEnvironment('foo_bar'));
        $this->assertNull($detector->detectEnvironment('_bar.php'));
        $this->assertNull($detector->detectEnvironment('foo_bar.php'));

        $detector->addEnvironmentRule('bar', ['bar']);

        $this->assertEquals('bar', $detector->detectEnvironment('bar'));

        $this->assertEquals('bar', $detector->detectEnvironment('foo.bar'));
        $this->assertEquals('bar', $detector->detectEnvironment('foo_bar'));
        $this->assertEquals('bar', $detector->detectEnvironment('foo-bar'));
        $this->assertEquals('bar', $detector->detectEnvironment('foo+bar'));
        $this->assertEquals('bar', $detector->detectEnvironment('foo:bar'));

        $this->assertEquals('bar', $detector->detectEnvironment('bar.foo'));
        $this->assertEquals('bar', $detector->detectEnvironment('bar_foo'));
        $this->assertEquals('bar', $detector->detectEnvironment('bar-foo'));
        $this->assertEquals('bar', $detector->detectEnvironment('bar+foo'));
        $this->assertEquals('bar', $detector->detectEnvironment('bar:foo'));

        $this->assertEquals('bar', $detector->detectEnvironment('foo_bar.foo'));
        $this->assertEquals('bar', $detector->detectEnvironment('foo-bar_foo'));
        $this->assertEquals('bar', $detector->detectEnvironment('foo+bar-foo'));
        $this->assertEquals('bar', $detector->detectEnvironment('foo:bar+foo'));
        $this->assertEquals('bar', $detector->detectEnvironment('foo_bar:foo'));
    }
}
