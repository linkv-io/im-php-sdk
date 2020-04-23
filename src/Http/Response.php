<?php

namespace LinkV\IM\Http;

use LinkV\IM\Exceptions\ResponseException;

/**
 * Class Response
 *
 * @package LinkV\IM
 */
class Response
{
    /**
     * @var int The code.
     */
    protected $code;
    /**
     * @var string The message.
     */
    protected $message;
    /**
     * @var array The data.
     */
    protected $data;

    /**
     * Instantiates a new Response super-class object.
     *
     * @param string $body
     *
     * @throws ResponseException
     */
    public function __construct($body)
    {
        $jsonData = json_decode($body, true);
        if ($jsonData == null) {
            throw new ResponseException("response decode error body:{$body}");
        }
        $this->code = $jsonData['code'] ?: -1;
        $this->message = $jsonData['msg'] ?: '';
        $this->data = $jsonData ?: null;
    }

    /**
     * return code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * return message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * return data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
