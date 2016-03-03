<?php

namespace Weew\Config;

interface IEnvironmentDetector {
    /**
     * @param $string
     *
     * @return string|null
     */
    function detectEnvironment($string);

    /**
     * @param $name
     * @param array $abbreviations
     */
    function addEnvironmentRule($name, array $abbreviations = []);

    /**
     * @param $name
     * @param array $abbreviations
     */
    function addIgnoreRule($name, array $abbreviations = []);
}
