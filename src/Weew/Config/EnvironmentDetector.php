<?php

namespace Weew\Config;

class EnvironmentDetector implements IEnvironmentDetector {
    /**
     * @var array
     */
    protected $environmentRules = [];

    /**
     * @var array
     */
    protected $ignoreRules = [];

    /**
     * EnvironmentDetector constructor.
     */
    public function __construct() {
        $this->addDefaultEnvironmentRules();
        $this->addDefaultIgnoreRules();
    }

    /**
     * @param $string
     *
     * @return null|string
     */
    public function detectEnvironment($string) {
        if ($this->matchIgnoreRules($string)) {
            return null;
        }

        if ($env = $this->matchEnvironmentRules($string)) {
            return $env;
        }

        return null;
    }

    /**
     * @param $name
     * @param array $abbreviations
     */
    public function addEnvironmentRule($name, array $abbreviations = []) {
        $this->environmentRules = $this->addRule($this->environmentRules, $name, $abbreviations);
    }

    /**
     * @param $name
     * @param array $abbreviations
     */
    public function addIgnoreRule($name, array $abbreviations = []) {
        $this->ignoreRules = $this->addRule($this->ignoreRules, $name, $abbreviations);
    }

    /**
     * @param array $rules
     * @param $name
     * @param array $abbreviations
     *
     * @return array
     */
    protected function addRule(array $rules, $name, array $abbreviations) {
        if (array_get($rules, $name) === null) {
            $rules[$name] = [];
        }

        $patterns = $this->createPatternsForStrings($abbreviations);

        foreach ($patterns as $pattern) {
            $rules[$name][] = $pattern;
        }

        return $rules;
    }

    /**
     * @param $string
     *
     * @return null|string
     */
    protected function matchEnvironmentRules($string) {
        return $this->matchRules($this->environmentRules, $string);
    }

    /**
     * @param $string
     *
     * @return bool
     */
    protected function matchIgnoreRules($string) {
        $match = $this->matchRules($this->ignoreRules, $string);

        return !! $match;
    }

    private function matchRules(array $rules, $string) {
        foreach ($rules as $name => $patterns) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $string) === 1) {
                    return $name;
                }
            }
        }

        return null;
    }

    /**
     * Register default environment rules.
     */
    protected function addDefaultEnvironmentRules() {
        $this->addEnvironmentRule('prod', ['prod', 'production']);
        $this->addEnvironmentRule('dev', ['dev', 'development']);
        $this->addEnvironmentRule('test', ['test']);
    }

    /**
     * Register default ignore rules.
     */
    protected function addDefaultIgnoreRules() {
        $this->addIgnoreRule('dist', ['dist', 'ignore']);
    }

    /**
     * @param array $strings
     *
     * @return string
     */
    protected function createPatternsForStrings(array $strings) {
        $patterns = [];

        foreach ($strings as $string) {
            $patterns[] = s('#(%s)$#', preg_quote("_$string"));
            $patterns[] = s('#(%s)#', preg_quote(s('_%s_', $string)));
            $patterns[] = s('#(%s)#', preg_quote("_$string."));
            $patterns[] = s('#^(%s)$#', preg_quote("$string"));
        }

        return $patterns;
    }
}
