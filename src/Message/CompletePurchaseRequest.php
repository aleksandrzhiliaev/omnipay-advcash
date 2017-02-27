<?php

namespace Omnipay\Advcash\Message;

use Omnipay\Common\Exception\InvalidResponseException;

class CompletePurchaseRequest extends AbstractRequest
{

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getData()
    {

        $theirHash = (string) $this->httpRequest->request->get('ac_hash');
        $ourHash = $this->createResponseHash($this->httpRequest->request->all());

        if ($theirHash !== $ourHash) {
            throw new InvalidResponseException("Callback hash does not match expected value");
        }

        return $this->httpRequest->request->all();
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    public function createResponseHash($parameters)
    {
        $alternate_password_hash = $this->getSecret();
        $fingerprint = "{$parameters['ac_transfer']}:{$parameters['ac_start_date']}:{$parameters['ac_sci_name']}:{$parameters['ac_src_wallet']}:{$parameters['ac_dest_wallet']}:{$parameters['ac_order_id']}:{$parameters['ac_amount']}:{$parameters['ac_merchant_currency']}:{$alternate_password_hash}";

        return hash('sha256', $fingerprint);
    }
}
