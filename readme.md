# Configuration made simple

[![Build Status](https://travis-ci.org/weew/php-config.svg?branch=master)](https://travis-ci.org/weew/php-config)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/weew/php-config/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/weew/php-config/?branch=master)
[![Coverage Status](https://coveralls.io/repos/weew/php-config/badge.svg?branch=master&service=github)](https://coveralls.io/github/weew/php-config?branch=master)
[![License](https://poser.pugx.org/weew/php-config/license)](https://packagist.org/packages/weew/php-config)

## Table of contents

- [Installation](#installation)
- [Loading configurations](#loading-configurations)
- [Cofiguration formats](#configuration-formats)
- [Environments](#environments)
    - [Setting an environment](#setting-an-environment)
    - [How it works](#how-it-works)
    - [Adding custom environments](#adding-custom-environments)
- [Extending](#extending)
    - [Custom config drivers](#custom-config-drivers)
    - [Custom environment detector](#custom-environment-detector)

## Installation

`composer require weew/php-config`

## Loading configurations

The config loader is responsible for loading of the configuration files. All you have to do is to provide a location(file or directory) where it can find your configs. It will scan the locations(s) for configuration files and return you a config object.

```php
$loader = new ConfigLoader();
$loader->addPath('path/to/my/config');
$config = $loader->load();
```

## Configuration formats

Currently, the only supported format is plain PHP. Simply create a file that returns an array with your configurations. I am planning to add the YAML driver some time soon.

```php
return [
    'my' => 'configurations',
    'db' => [
        'database' => 'foo',
        'username' => 'root',
        'password' => 'bar',
    ],
];
```

## Environments

Often you want to split your configurations in multiple files or directories and load them depending on your current environment. This can be achieved trought the environment settings.

### Setting an environment

Out of the box it comes with support for dev, test and prod environments. Custom environments can be added on the fly.

```php
$loader->setEnviroonment('prod');
```

### How it works

To understand how environments detection works, lets take a look at this directory structure:

```
- test
    - db.php
- prod
    - db.php
- config.php
- config_test.php
- config_prod.php
```

In the test environment only the "test" directory and it's contents, "config\_test.php" and "config.php" will be loaded. In the prod environment however, it will load only the "prod" directory, "config\_prod.php" and "config.php".

Files and folders that have been added to the config loader will be loaded in the order of registration. Inside directories, files are loaded alphabetically.

### Adding custom environments

To create your own environments you'll have to register a new rule on the environment detector. The first argument is the name of the environment and the second is an array of masks.

```php
$loader->getEnvironmentDetector()
    ->addRule('integ', ['integ', 'integration', 'stage']);
```

Below is a list of some files and directories that would match the integration environment:

```
- stage
- _stage
- foo_stage
- _stage.txt
- foo_stage.txt
```

This files will not match:

```
- stagefoo
- foostage
- foo_stage_bar
- _stagebar
```

## Extending

Config loader provides you multiple extension points to alter its behaviour and functionality.

### Custom config drivers

Adding your own drivers is very easy. All you have to do is to implement the IConfigDriver interface and pass an instance of the driver to the config loader. You can have multiple active drivers at the same time.

```php
class MyDriver implements IConfigDriver {}

$loader->addDriver(new MyDriver());
```

### Custom environment detector

You can replace the default environment detector with your own. Just create a new detector which implements the IEnvironmentDetector interface and pass it to the config loader. Also take a look on how to [create your own environments](#adding-custom-environments).

```php
class MyDetector implements IEnvironmentDetector {}

$loader->setEnvironmentDetector(new MyDetector());
```
