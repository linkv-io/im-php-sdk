<?php
namespace LinkV\IM;

use LinkV\IM\HTTP\Request;
use LinkV\IM\HTTP\Response;

interface SocketInterface
{
    public function send(Request $req): Response;
}
