<?php

namespace LinkV\Im;

final class Tools
{
    static function String2Hex($string): string
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }

    static function Hex2String($hex): string
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }

    static function GetTimestamp(): float
    {
        list($usec, $sec) = explode(" ", microtime());
        return (float)$sec;
    }

    static function genGUID(): string
    {
        $charID = strtoupper(md5(uniqid(mt_rand(), true)));
        $hyphen = chr(45);// "-"
        return chr(123)// "{"
            . substr($charID, 0, 8) . $hyphen
            . substr($charID, 8, 4) . $hyphen
            . substr($charID, 12, 4) . $hyphen
            . substr($charID, 16, 4) . $hyphen
            . substr($charID, 20, 12)
            . chr(125);// "}"
    }
}
