<?php

namespace LinkV\Im;

use LinkV\Im\Exceptions\ResponseException;
use LinkV\Im\Exceptions\SDKException;
use LinkV\Im\Http\Socket;
use LinkV\Im\Http\Request;

class Im
{
    protected $uri = 'http://imapi-pre.ksmobile.net/';
    protected $handler;
    protected $appID;
    protected $appKey;

    public function __construct(string $appID, string $appSecret, SocketInterface $handler = null)
    {
        $this->appID = $appID ?: '';
        $this->appKey = $appSecret ?: '';
        if ($this->appID == 0 || $this->appKey == 0) {
            throw new SDKException('app_id or app_secret is invalid');
        }

        $this->handler = $handler ?: new Socket();
    }

    public function pushSystemMessage(string $from, string $to, string $objectName, string $content,
                                      string $pushContent = null, string $pushData = null, string $deviceID = null,
                                      string $toUserAppID = null, string $toUserExtSysUserID = null): bool
    {
        $url = $this->uri . "task/rest/{$this->appID}/converse/pushSysMsg";
        $req = new Request($url);

        $resp = $this->handler->send($req);

        if ($resp == null) {
            throw new SDKException('');
        }

        if ($resp->getCode() != SUCCESS) {
            throw new ResponseException('');
        }
        return true;
    }
}