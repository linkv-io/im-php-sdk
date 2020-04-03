<?php

namespace LinkV\Im\Http;

class Response
{
    protected $code;
    protected $message;
    protected $data;

    public function __construct(int $code, string $body)
    {

    }

    public function getCode()
    {
        return $this->code;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
