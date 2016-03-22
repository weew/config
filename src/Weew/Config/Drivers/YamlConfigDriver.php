<?php

namespace Weew\Config\Drivers;

use Symfony\Component\Yaml\Yaml;
use Weew\Config\IConfigDriver;

class YamlConfigDriver implements IConfigDriver {
    /**
     * @param $path
     *
     * @return bool
     */
    public function supports($path) {
        return file_get_extension($path) == 'yml';
    }

    /**
     * @param $path
     *
     * @return array
     */
    public function loadFile($path) {
        $content = trim(file_read($path));
        $config = [];

        if ( ! empty($content)) {
            $config = Yaml::parse($content);
        }

        return $config;
    }
}
