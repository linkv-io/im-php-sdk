<?php

namespace LinkV\IM\Http;

use LinkV\IM\Tools;

/**
 * Class Request
 *
 * @package LinkV\IM
 */
class Request
{
    /**
     * @var string The url.
     */
    protected $url;
    /**
     * @var array The header.
     */
    protected $header;
    /**
     * @var array The params.
     */
    protected $params;

    /**
     * Instantiates a new Request super-class object.
     *
     * @param string $url
     * @param array $header
     * @param array $params
     *
     */
    public function __construct($url, $header, $params)
    {
        $this->url = $url;

        $this->header = $header;
        $this->params = $params;
    }

    /**
     * Instantiates a new Request super-class object.
     *
     * @param string $url
     * @param string $appID
     * @param string $appKey
     * @param array $header
     * @param array $params
     *
     * @return Request
     */
    public static function makeRequestWithSign($url, $appID, $appKey, $header, $params)
    {
        $timestamp = Tools::GetTimestamp();
        $nonce = Tools::genGUID();

        $header['appId'] = $appID;
        $header['appKey'] = $appKey;
        $header['timestamp'] = $timestamp;
        $header['nonce'] = $nonce;
        $header['sign'] = sha1("{$appID}|{$appKey}|{$timestamp}|{$nonce}");

        $params['sign'] = hash_hmac('sha256', http_build_query($params), $appKey);

        return new Request($url, $header, $params);
    }

    /**
     * return url
     *
     * @return string
     */
    public function getURL()
    {
        return $this->url;
    }

    /**
     * return headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->header;
    }

    /**
     * return params
     *
     * @return array
     */
    public function getParams()
    {

        return $this->params;
    }
}
