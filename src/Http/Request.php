<?php

namespace LinkV\Im\Http;

use LinkV\Im\Tools;

class Request
{
    protected $url;
    protected $appID;
    protected $appKey;
    protected $params;

    public function __construct(string $url, string $appID, string $appKey, array $params)
    {
        $this->url = $url;
        $this->appID = $appID;
        $this->appKey = $appKey;
        $this->params = $params;
    }

    public function getURL(): string
    {
        return $this->url;
    }

    public function getHeaders(): array
    {
        $timestamp = Tools::GetTimestamp();
        $nonce = Tools::genGUID();

        $s = sha1("{$this->appID}|{$this->appKey}|{$timestamp}|{$nonce}", true);
        $sign = Tools::String2Hex($s);

        return [
            'appId' => $this->appID,
            'timestamp' => $timestamp,
            'nonce' => $nonce,
            'sign' => $sign,
        ];
    }

    public function getParams(): array
    {
        $s = hash_hmac('sha256', http_build_query($this->params), $this->appKey, true);
        $this->params['sign'] = Tools::String2Hex($s);

        return $this->params;
    }
}
