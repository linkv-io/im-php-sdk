<?php

namespace LinkV\IM;

use LinkV\IM\HTTP\Request;
use LinkV\IM\HTTP\Response;

/**
 * Interface
 *
 * @package LinkV\IM
 */
interface SocketInterface
{
    /**
     * send 发送请求
     *
     * @param Request $req
     *
     * @return Response
     */
    public function send($req);
}
