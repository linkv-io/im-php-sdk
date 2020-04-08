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
     * @var string The app id.
     */
    protected $appID;
    /**
     * @var string The app key.
     */
    protected $appKey;
    /**
     * @var array The params.
     */
    protected $params;

    /**
     * Instantiates a new Request super-class object.
     *
     * @param string $url
     * @param string $appID
     * @param string $appKey
     * @param array $params
     *
     */
    public function __construct($url, $appID, $appKey, $params)
    {
        $this->url = $url;
        $this->appID = $appID;
        $this->appKey = $appKey;
        $this->params = $params;
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
        $timestamp = Tools::GetTimestamp();
        $nonce = Tools::genGUID();

//        $s = sha1("{$this->appID}|{$this->appKey}|{$timestamp}|{$nonce}", true);
//        $sign = Tools::Hex2String($s);

        $sign = sha1("{$this->appID}|{$this->appKey}|{$timestamp}|{$nonce}");

        return [
            'appId' => $this->appID,
            'timestamp' => $timestamp,
            'nonce' => $nonce,
            'sign' => $sign,
        ];
    }

    /**
     * return params
     *
     * @return array
     */
    public function getParams()
    {
//        $s = hash_hmac('sha256', http_build_query($this->params), $this->appKey, true);
//        $this->params['sign'] = Tools::Hex2String($s);

        $this->params['sign'] = hash_hmac('sha256', http_build_query($this->params), $this->appKey);
        return $this->params;
    }
}
