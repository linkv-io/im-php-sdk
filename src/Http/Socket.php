<?php

namespace LinkV\IM\Http;

use LinkV\IM\Exceptions\ResponseException;
use LinkV\IM\SocketInterface;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class Socket
 *
 * @package LinkV\IM
 */
class Socket implements SocketInterface
{
    /**
     * send 发送请求
     *
     * @param Request $req
     *
     * @return Response
     *
     * @throws ResponseException
     */
    public function send($req)
    {
        $params = http_build_query($req->getParams());

        $client = new Client();
        try {
            $res = $client->request('GET', "{$req->getURL()}?{$params}", [
                'headers' => $req->getHeaders(),
            ]);
            if ($res->getStatusCode() != 200) {
                throw new ResponseException($res->getReasonPhrase());
            }
            return new Response($res->getBody());
        }catch (GuzzleException $e){
            throw new ResponseException($e->getMessage());
        }
    }
}