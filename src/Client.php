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
     * @var string The app Secret.
     */
    protected $appSecret;

    /**
     * Instantiates a new Client super-class object.
     *
     * @param string $appID
     * @param $appKey
     * @param string $appSecret
     * @param SocketInterface|null $handler
     *
     * @throws SDKException
     */
    public function __construct($appID, $appKey, $appSecret, $handler = null)
    {
        $this->appID = $appID ?: '';
        $this->appKey = $appKey ?: '';
        $this->appSecret = $appSecret ?: '';
        if (strlen($this->appID) == 0 || strlen($this->appKey) == 0 || strlen($this->appSecret) == 0) {
            throw new SDKException('params error');
        }

        $this->handler = $handler ?: new Socket();
    }

    /**
     * setHost 更换私有云服务器地址
     *
     * @param string $host 服务器地址
     *
     */
    public function setHost($host)
    {
        $this->uri = $host;
    }

    /**
     * pushSystemMessage 发送系统消息
     *
     * @param string $fromUserID 发送用户标识
     * @param string $toUserID 接受用户，支持批量，最大5000人，批量的情况下，用户标识使用逗号(,)隔开
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
    public function pushSystemMessage($fromUserID, $toUserID, $objectName, $content,
                                      $pushContent = null, $pushData = null, $deviceID = null,
                                      $toUserAppID = null, $toUserExtSysUserID = null)
    {
        $token = $this->getUserToken($fromUserID);
        if ($token == '') {
            throw new ResponseException("token is empty");
        }

        $header = [
            'appUid' => $fromUserID,
            'cmimToken' => $token,
        ];
        $params = [
            'appId' => $this->appID,
            'from' => $fromUserID,
            'to' => $toUserID,
            'objectName' => $objectName,
            'content' => $content,
            'pushContent' => $pushContent ?: '',
            'pushData' => $pushData ?: '',
            'deviceId' => $deviceID ?: '',
            'toUserAppid' => $toUserAppID ?: '',
            'toUserExtSysUserId' => $toUserExtSysUserID ?: '',
        ];

        $url = $this->uri . "/task/rest/{$this->appID}/converse/pushSysMsg";
        $req = Request::makeRequestWithSign($url, $this->appID, $this->appKey, $header, $params);

        $resp = $this->handler->send($req);
        if ($resp->getCode() != 200) {
            throw new ResponseException("code({$resp->getCode()}) not 200,  {$resp->getMessage()}");
        }
        return $resp;
    }

    /**
     * sendServerMessage 发送直播间消息
     *
     * @param string $fromUserID 用户标识
     * @param string $toRoomID 直播间标识
     * @param string $objectName 消息的自定义类型
     * @param string $content 消息的内容，最好为JSON数据的toString
     *
     * @return Response
     *
     * @throws ResponseException
     */
    public function sendServerMessage($fromUserID, $toRoomID, $objectName, $content)
    {

        $token = $this->getUserToken($fromUserID);
        if ($token == '') {
            throw new ResponseException('token is empty');
        }

        $header = [
            'appUid' => $fromUserID,
            'cmimToken' => $token,
        ];
        $params = [
            'appId' => $this->appID,
            'fromUserId' => $fromUserID,
            'toChatroomId' => $toRoomID,
            'objectName' => $objectName,
            'content' => $content,
        ];

        $url = $this->uri . "/im/innerrest/{$this->appID}/sendservermsg";
        $req = Request::makeRequestWithSign($url, $this->appID, $this->appKey, $header, $params);
        $resp = $this->handler->send($req);
        if ($resp->getCode() != 200) {
            throw new ResponseException("code({$resp->getCode()}) not 200,  {$resp->getMessage()}");
        }
        return $resp;
    }

    /**
     * getUserToken 获取用户token
     *
     * @param string $userID 用户标识
     *
     * @return string  用户token
     *
     * @throws ResponseException
     */
    protected function getUserToken($userID)
    {
        $timestamp = Tools::GetTimestamp();
        $nonce = Tools::genGUID();
        $arr = [
            $nonce,
            $this->appSecret,
            $timestamp,
        ];

        sort($arr, SORT_STRING);

        $sign = md5(join('', $arr));
        $header = [
            'nonce' => $nonce,
            'timestamp' => $timestamp,
            'appkey' => $this->appKey,
            'sign' => $sign,
            'signature' => $sign,
        ];
        $params = [
            'userId' => $userID,
        ];
        $url = $this->uri . "/api/rest/getToken";
        $req = new Request($url, $header, $params);
        $resp = $this->handler->send($req);
        if ($resp->getCode() != 200) {
            throw new ResponseException("code({$resp->getCode()}) not 200,  {$resp->getMessage()}");
        }
        return $resp->getData()['token'] ?: '';
    }
}
