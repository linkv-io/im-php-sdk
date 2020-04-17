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
     * @param string $from 发送用户标识
     * @param string $to 接受用户，支持批量，最大5000人，批量的情况下，用户标识使用逗号(,)隔开
     * @param string $objectName 消息类型，例如(RC:ImgMsg)
     * @param string $content 发送消息的内容，可以被解析成json对象的字符串
     * @param string|null $pushContent 推送的内容
     * @param string|null $pushData 发送消息推送的数据，可以被解析成json对象的字符串，pushContent，pushData 是作为离线推送APNS，GSM的依据
     * @param string|null $deviceID 发送设置的标识
     * @param string|null $toUserAppID 接收方appid(用于选择推送通道)
     * @param string|null $toUserExtSysUserID 接收方外部系统用户id(用于选择推送通道)
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

    /**
     * sendServerMessage 发送直播间消息
     *
     * @param string $userID 用户标识
     * @param string $roomID 直播间标识
     * @param string $objectName 消息的自定义类型
     * @param string $content 消息的内容，最好为JSON数据的toString
     *
     * @return Response
     *
     * @throws ResponseException
     */
    public function sendServerMessage($userID, $roomID, $objectName, $content)
    {
        $params = [
            'appId' => $this->appID,
            'fromUserId' => $userID,
            'toChatroomId' => $roomID,
            'objectName' => $objectName,
            'content' => $content,
        ];

        $url = $this->uri . "/im/innerrest/{$this->appID}/sendservermsg";
        $req = new Request($url, $this->appID, $this->appKey, $params);

        $resp = $this->handler->send($req);
        if ($resp->getCode() != 200) {
            throw new ResponseException('');
        }
        return $resp;
    }
}