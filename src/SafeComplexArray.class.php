<?php

class SafeComplexArray {
    private $safe_array = array();

    /**
     * @param array $array
     */
    public function __construct(array $array) {
        $this->cleanArray($array);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getArray() {
        if(!isset($this->safe_array)) throw new Exception("No array has been declared");

        return $this->safe_array;
    }

    private function cleanArray(&$array) {
        array_walk_recursive($array, "SafeComplexArray::filter");

        $this->safe_array = $array;
    }

    private static function filter(&$value) {
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}