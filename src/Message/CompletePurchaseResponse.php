<?php

namespace Omnipay\Advcash\Message;

use Omnipay\Common\Message\AbstractResponse;


class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return ($this->data['ac_transaction_status'] == 'COMPLETED') ? true : false;
    }

    public function isCancelled()
    {
        return ($this->data['ac_transfer'] == 0) ? true : false;
    }

    public function isRedirect()
    {
        return false;
    }

    public function getRedirectUrl()
    {
        return null;
    }

    public function getRedirectMethod()
    {
        return null;
    }

    public function getRedirectData()
    {
        return null;
    }

    public function getTransactionId()
    {
        return isset($this->data['ac_order_id']) ? $this->data['ac_order_id'] : null;
    }

    public function getAmount()
    {
        return isset($this->data['ac_amount']) ? $this->data['ac_amount'] : null;
    }

    public function getTransactionReference()
    {
        return isset($this->data['ac_transfer']) and $this->data['ac_transfer'] != 0 ? $this->data['ac_transfer'] : null;
    }

    public function getCurrency()
    {
        return $this->data['ac_merchant_currency'];
    }

    public function getMessage()
    {
        return null;
    }
}
