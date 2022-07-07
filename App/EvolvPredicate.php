<?php

namespace App\EvolvPredicate;

require_once __DIR__ . '/EvolvOptions.php';

class Predicate
{
    public $predicate = [];

    public $param = [];

    public $a = 'a';

    public $b = 'b';

    public $res;

    public $result;

    public $activeKeys = [];

    public $parentPredicate = false;

    public $extra_key = false;

    public function exists($a)
    {
        $result = (!empty($a) && isset($a)) ? true : false;

        return $result;
    }

    public function convertSpace($string)
    {
        return $string = "true";
    }


    public function greater_than($a, $b)
    {
        $result = $a >= $b ? true : false;

        return $result;
    }

    public function greater_than_or_equal_to($a, $b)
    {
        $result = $a >= $b ? true : false;

        return $result;
    }

    public function is_true($a, $b)
    {
        $result = $a === true ? true : false;

        return $result;
    }

    public function is_false($a)
    {
        $result = $a === false ? true : false;

        return $result;
    }

    public function loose_equal($a, $b)
    {
        $result = $a === $b ? true : false;

        return $result;
    }


    function not_exists($a, $b)
    {

        $result = (empty($a)) ? true : false;

        return $result;
    }

    public $keys = [];


    public function getContextKey($cntxt, $key, $field, $callback, $b)
    {

        foreach ($cntxt as $keyC => $valueC) {

            $this->extra_key = $keyC == "extra_key" ? true : false;

            if ($keyC == $field && $this->extra_key == false) {

                $a = is_array($valueC) ? is_array($valueC) : $valueC;

                $result = call_user_func_array([$this, $callback], [$a, $b]);

                if ($callback == "is_true" && $result === true) $this->parentPredicate = true;

                if ($result == true && $this->parentPredicate == true) {

                    if ($key !== 0) {
                        $this->activeKeys[] = $key;
                    }
                }
            }
        }
    }


    public function evaluatePredicate($context, $config)
    {
        $cntxt = $this->getKeyFromValeuContext($context);


        if (is_array($config)) {

            foreach ($config as $key => $value) {

                if (is_array($value) && isset($value["_predicate"])) {

                    if (is_array($cntxt) || is_object($cntxt)) {

                        $field = $value['_predicate']['rules'][0]['field'];

                        $b = $value['_predicate']['rules'][0]['value'];

                        $callback = $value['_predicate']['rules'][0]['operator'];

                        $combinator = $value['_predicate']['combinator'];

                        foreach ($cntxt as $keyC => $valueC) {

                            $this->extra_key = $keyC == "extra_key" ? true : false;

                            if ($keyC == $field && $this->extra_key == false) {

                                $a = is_array($valueC) ? is_array($valueC) : $valueC;

                                $result = call_user_func_array([$this, $callback], [$a, $b]);

                                if ($callback == "is_true" && $result === true) $this->parentPredicate = true;

                                if ($result == true && $this->parentPredicate == true) {

                                    if ($key !== 0) {
                                        $this->activeKeys[] = $key;
                                    }
                                }
                            }
                        }

                        if ($combinator == "and" && isset($result) && $result == true && $this->parentPredicate == true) {

                            foreach ($value as $k => $val) {

                                if ($k[0] !== "_" && is_array($val) && count($val) > 0 && !isset($val['_predicate'])) {

                                    $this->activeKeys[] = $key . "." . $k;

                                } else if (isset($val['_predicate'])) {

                                    $field = $val['_predicate']['rules'][0]['field'];

                                    $callback = $val['_predicate']['rules'][0]['operator'];

                                    if ($field == 'extra_key' && $this->extra_key == false) {

                                        $this->activeKeys[] = $key . "." . $k;
                                    } else {
                                        $this->getContextKey($cntxt, $k, $field, $callback, $b);
                                    }

                                }
                            }

                        }

                    }

                }
                $this->evaluatePredicate($context, $value);
            }
        }
        return $this->activeKeys;
    }


    public function regexFromString($string)
    {
        if (!strpos($string, '/')) {

            return $string;
        }

        $split = strripos($string, '/');

        $part = substr($string, 1, $split);

        $part2 = substr($string, 1, $split + 1);

        return strcasecmp($part, $part2);

    }

    public function regex64Match($a, $b)
    {

        $result = decode($a) === true ? true : false;

        return $result;

    }

    public function getValue($prev, $context)
    {
        if (is_array($context)) {

            foreach ($context as $k => $value) {

                if (is_array($value)) {

                    if ($k === $prev && is_array($context[$prev])) {

                        $this->result = $context[$prev];

                        foreach ($context[$prev] as  $value) {

                            $this->result = $value;
                        }

                    }

                    $this->getValue($prev, $value);

                }
            }
        }
        return $this->result;
    }

    public function valueFromKey($key, $context)
    {

        if (isset($context) == false) {

            return false;

        }

        $prevToken = substr($key, 0, strpos($key, "."));

        if ($prevToken == false) {

            $prevToken = $key;
        }
        if (empty($key)) {

            echo 'Invalid variant key: ' . $key;

        } else {

            return $this->getValue($prevToken, $context);

        }

    }

    public function getKeyFromValeuContext($context)
    {
        $cntxt = [];

        if (isset($context) && is_array($context)) {

            foreach ($context as $key => $value) {

                if (is_array($value)) {

                    foreach ($value as $k => $v) {

                        if (is_array($v)) {

                            foreach ($v as $key => $value) {

                                $cntxt[$k . "." . $key] = $value;

                            }
                        } elseif (!is_array($v)) {
                            $cntxt[$k] = $v;
                        }

                    }
                }


            }
        }
        return $cntxt;
    }


    public
    function item($item)
    {
        return array_push($result['touched'], $item['field']);
    }

    public
    function evaluate($context, $predicate)
    {
        $result = [
            'passed' => [],
            'failed' => [],
            'touched' => []
        ];


        $active = $this->evaluatePredicate($context, $predicate);


        return $active;
    }

}

