<?php
namespace LinkV\Im\Http;

use LinkV\Im\SocketInterface;

class Socket implements SocketInterface
{
    public function send(Request $req): Response
    {
        return new Response();
    }
}