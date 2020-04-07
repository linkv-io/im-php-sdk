<?php

namespace LinkV\Im\Http;

class Response
{
    protected $code;
    protected $message;
    protected $data;

    public function __construct(string $body)
    {
        $jsonData = json_decode($body);
        $this->code = $jsonData['code'];
        $this->message = $jsonData['msg'];
        $this->data = $jsonData['data'];
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
