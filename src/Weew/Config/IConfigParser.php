<?php

namespace Weew\Config;

interface IConfigParser {
    /**
     * @param IConfig $config
     * @param $value
     *
     * @return mixed
     */
    function parse(IConfig $config, $value);
}
