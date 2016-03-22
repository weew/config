<?php

namespace Weew\Config\Drivers;

use Weew\Config\Exceptions\InvalidConfigFormatException;
use Weew\Config\IConfigDriver;

class JsonConfigDriver implements IConfigDriver {
    /**
     * @param $path
     *
     * @return bool
     */
    public function supports($path) {
        return file_get_extension($path) == 'json';
    }

    /**
     * @param $path
     *
     * @return array
     * @throws InvalidConfigFormatException
     */
    public function loadFile($path) {
        $content = trim(file_read($path));
        $config = [];

        if ( ! empty($content)) {
            $config = json_decode($content, true);

            if ( ! is_array($config)) {;
                throw new InvalidConfigFormatException(
                    s('JsonConfigDriver expects config at path %s to be a valid json file.', $path)
                );
            }
        }

        return $config;
    }
}
