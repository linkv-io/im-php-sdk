<?php

namespace LinkV\Im\Http;

use LinkV\Im\Exceptions\ResponseException;
use LinkV\Im\SocketInterface;

use GuzzleHttp\Client;

class Socket implements SocketInterface
{
    public function send(Request $req): Response
    {
        $params = http_build_query($req->getParams());

        $client = new Client();
        $res = $client->request('GET', "{$req->getURL()}?{$params}", [
            'headers' => $req->getHeaders(),
        ]);
        if ($res->getStatusCode() != 200) {
            throw new ResponseException('');
        }
        return new Response($res->getBody());
    }
}