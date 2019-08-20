<?php

namespace Omnipay\SagePay\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Register a card with the gateway in exchange for a token.
 */
class DirectTokenRegistrationRequest extends AbstractRequest
{
    /**
     * @var array
     */
    protected $cardBrandMap = array(
        'mastercard' => 'mc',
        'diners_club' => 'dc'
    );

    /**
     * @return string
     */
    public function getService()
    {
        return static::SERVICE_TOKEN;
    }

    /**
     * @return string
     */
    public function getTxType()
    {
        return static::TXTYPE_TOKEN;
    }

    /**
     * @return array|mixed
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('card');

        $data = $this->getBaseData();

        $data['VendorTxCode'] = $this->getTransactionId();
        $data['Currency'] = $this->getCurrency();
        $data['CardHolder'] = $this->getCard()->getBillingName();
        $data['CardNumber'] = $this->getCard()->getNumber();
        $data['ExpiryDate'] = $this->getCard()->getExpiryDate('my');
        $data['CV2'] = $this->getCard()->getCvv();
        $data['CardType'] = $this->getCardBrand();

        // The account type only comes into play when a transaction is requested.
        unset($data['AccountType']);

        return $data;
    }

    /**
     * @return mixed|string
     */
    protected function getCardBrand()
    {
        $brand = $this->getCard()->getBrand();

        if (isset($this->cardBrandMap[$brand])) {
            return $this->cardBrandMap[$brand];
        }

        return $brand;
    }
}
