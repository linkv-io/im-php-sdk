<?php

namespace LinkV\IM;

/**
 * Class Tools
 *
 * @package LinkV\IM
 */
final class Tools
{
    /**
     * Hex2String is byte => string
     *
     * @param string $hex
     *
     * @return string
     */
    static function Hex2String($hex)
    {
        $string = '';
        for ($i = 0; $i < strlen($hex); $i++) {
            $string .= dechex(ord($hex[$i]));
        }
        return $string;
    }

    /**
     * String2Hex is string => byte
     *
     * @param string $string
     *
     * @return string
     */
    static function String2Hex($string)
    {
        $hex = '';
        for ($i = 0; $i < strlen($string) - 1; $i += 2) {
            $hex .= chr(hexdec($string[$i] . $string[$i + 1]));
        }
        return $hex;
    }

    /**
     * GetTimestamp 获取时间戳
     *
     * @return float
     */
    static function GetTimestamp()
    {
        $arr = explode(" ", microtime());
        return (float)$arr[1];
    }

    /**
     * genGUID 获取guid
     *
     * @return string
     */
    static function genGUID()
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
