<?php

namespace LinkV\IM;

use LinkV\IM\Exceptions\ResponseException;
use LinkV\IM\Exceptions\SDKException;
use LinkV\IM\Http\Response;
use LinkV\IM\Http\Socket;
use LinkV\IM\Http\Request;

/**
 * Class Client
 *
 * @package LinkV\IM
 */
class Client
{
    /**
     * @var string The Url.
     */
    protected $uri = 'http://imapi-qa.ksmobile.net';
    /**
     * @var SocketInterface The http handle.
     */
    protected $handler;
    /**
     * @var string The app ID.
     */
    protected $appID;
    /**
     * @var string The app Key.
     */
    protected $appKey;

    /**
     * Instantiates a new Client super-class object.
     *
     * @param string $appID
     * @param string $appSecret
     * @param SocketInterface|null $handler
     *
     * @throws SDKException
     */
    public function __construct($appID, $appSecret, $handler = null)
    {
        $this->appID = $appID ?: '';
        $this->appKey = $appSecret ?: '';
        if (strlen($this->appID) == 0 || strlen($this->appKey) == 0) {
            throw new SDKException('app_id or app_secret is invalid');
        }

        $this->handler = $handler ?: new Socket();
    }

    /**
     * pushSystemMessage 发送系统消息
     *
     * @param string $from
     * @param string $to
     * @param string $objectName
     * @param string $content
     * @param string|null $pushContent
     * @param string|null $pushData
     * @param string|null $deviceID
     * @param string|null $toUserAppID
     * @param string|null $toUserExtSysUserID
     *
     * @return Response
     *
     * @throws ResponseException
     */
    public function pushSystemMessage($from, $to, $objectName, $content,
                                      $pushContent = null, $pushData = null, $deviceID = null,
                                      $toUserAppID = null, $toUserExtSysUserID = null)
    {
        $params = [
            'appId' => $this->appID,
            'from' => $from,
            'to' => $to,
            'objectName' => $objectName,
            'content' => $content,
            'pushContent' => $pushContent ?: '',
            'pushData' => $pushData ?: '',
            'deviceId' => $deviceID ?: '',
            'toUserAppid' => $toUserAppID ?: '',
            'toUserExtSysUserId' => $toUserExtSysUserID ?: '',
        ];

        $url = $this->uri . "/task/rest/{$this->appID}/converse/pushSysMsg";
        $req = new Request($url, $this->appID, $this->appKey, $params);

        $resp = $this->handler->send($req);
        if ($resp->getCode() != 200) {
            throw new ResponseException('');
        }
        return $resp;
    }
}