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
    public function prefix($_input, $_a)
    {
        $result = [];
        $pairs = explode(';', $_input);
        foreach ($pairs as $pair) {
            list($start, $end) = explode('-', $pair);
            $result += $this->calc($start, $end, $_a);
        }

//        exit();

        return $result;
    }

    public function calc($start, $end, $_a)
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
                $result[] = $common . $i;
            }

            return $result;
        }

        prn($common, $_s, $_e);

        return $result;

        echo "\n";
        $inStart = str_pad($common, strlen($start), '0');
        $inEnd = str_pad($common, strlen($end), '9');

        if ($inStart == $start && $inEnd == $end) {
            $result[] = $common;
            prn($common, $inStart, $inEnd, $_a);

            return $result;
        }


        echo "\n";
        prn($common, $inStart, $inEnd);

        $inStart = $common . '0';
        $inEnd = $common . '9';

        if ($inStart != substr($start, 0, strlen($inStart))) {
            $inStart = substr($start, 0, strlen($inStart));
        }

        if ($inEnd != substr($end, 0, strlen($inEnd))) {
            $inEnd = substr($end, 0, strlen($inEnd));
        }

        prn();
        prn($common, $inStart, $inEnd, $_a);
        exit();

//            print_r([$start, $end, (int)$start & (int)$end]);
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