<?php

namespace Omnipay\Advcash\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Advcash\Support\Helpers;

class RefundResponse extends AbstractResponse
{
    protected $message;
    protected $success;

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
        $this->success = false;
        $this->parseResponse();
    }

    public function isSuccessful()
    {
        return $this->success;
    }

    public function getMessage()
    {
        return $this->message;
    }

    private function parseResponse()
    {
        if ($this->data->return) {
            $this->success = true;
        }
    }

}
