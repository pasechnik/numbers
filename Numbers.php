<?php
require_once('debug.inc.php');
define('DEBUG', 1);

/**
 * Created by PhpStorm.
 * User: Master
 * Date: 5/21/2015
 * Time: 09:11
 */
class Numbers
{

    /**
     * @param string $_input
     * @return array
     */
    public function prefix($_input)
    {
        $result = [];
        $pairs = explode(';', $_input);
        foreach ($pairs as $pair) {
            list($start, $end) = explode('-', $pair);
            $result = array_merge($result, $this->calc($start, $end));
        }

//        $result = array_unique($result);

        return $result;
    }

    public function calc($start, $end)
    {
        $result = [];

        while (substr($start, -1) === '0' && substr($end, -1) === '9') {
            $start = substr($start, 0, -1);
            $end = substr($end, 0, -1);
        }

        if ($start == $end) {
            $result[] = $start;

            return $result;
        }

        $common = $this->getCommonPart([$start, $end]);
        $_s = substr($start, strlen($common));
        $_e = substr($end, strlen($common));

        if ($_e - $_s < 9) {
            for ($i = $_s; $i <= $_e; $i++) {
                $result[] = $common . str_pad($i,strlen($_s),'0', STR_PAD_LEFT);
            }

            return $result;
        }

        if ($start !== substr($start, 0, -1) . '0') {
            $result = $this->calc($start, substr($start, 0, -1) . '9');
            $start = substr($start, 0, -2) . ((int)substr($start, -2, 1) + 1) . '0';
        }

        $resultEnd = [];
        if ($end !== substr($end, 0, -1) . '9') {
            $eStart = substr($end, 0, -1) . '0';
            $resultEnd = $this->calc($eStart, $end);
            $end = substr($end, 0, -2) . (substr($end, -2, 1) - 1) . '9';
        }

        $result = array_merge($result, $this->calc($start, $end));
        $result = array_merge($result, $resultEnd);

        return $result;

    }

    /**
     * @param array $strings
     * @return string
     */
    public
    function getCommonPart(
        $strings
    ) {
        switch (count($strings)) {

            case 0:
                return "";

            case 1:
                return $strings[0];

            case 2:
                // compute the prefix between the two strings
                $a = $strings[0];
                $b = $strings[1];
                $n = min(strlen($a), strlen($b));
                $i = 0;
                while ($i < $n && $a[$i] === $b[$i]) {
                    ++$i;
                }

                return substr($a, 0, $i);

            default:
                // return the common prefix of the first string,
                // and the common prefix of the rest of the strings
                return $this->getPrefix([$strings[0], $this->getPrefix(array_slice($strings, 1))]);
        }
    }
}