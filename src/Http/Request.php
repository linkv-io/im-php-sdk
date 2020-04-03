<?php
namespace LinkV\Im\Http;

class Request
{
    protected $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }
}
