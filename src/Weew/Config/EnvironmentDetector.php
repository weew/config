<?php

namespace Weew\Config;

class EnvironmentDetector implements IEnvironmentDetector {
    /**
     * @var array
     */
    protected $rules = [];

    public function __construct() {
        $this->addRule('prod', ['prod', 'production']);
        $this->addRule('dev', ['dev', 'development']);
        $this->addRule('test', ['test']);
    }

    /**
     * @param $string
     *
     * @return null|string
     */
    public function detectEnvironment($string) {
        foreach ($this->rules as $name => $patterns) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $string) === 1) {
                    return $name;
                }
            }
        }

        return null;
    }

    /**
     * @param $name
     * @param array $abbreviations
     */
    public function addRule($name, array $abbreviations) {
        $pattern = $this->createPatternForStrings($abbreviations);

        if (array_get($this->rules, $name) === null) {
            $this->rules[$name] = [];
        }

        $this->rules[$name][] = $pattern;
    }

    /**
     * @param array $strings
     *
     * @return string
     */
    protected function createPatternForStrings(array $strings) {
        $groups = [];

        foreach ($strings as $string) {
            $groups[] = preg_quote("_$string");
        }

        $pattern = s('#%s#', implode('|', $groups));

        return $pattern;
    }
}
