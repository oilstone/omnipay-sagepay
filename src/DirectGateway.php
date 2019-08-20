<?php

namespace Omnipay\SagePay;

use BadMethodCallException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\SagePay\Message\DirectAuthorizeRequest;
use Omnipay\SagePay\Message\DirectCompleteAuthorizeRequest;
use Omnipay\SagePay\Message\DirectCompletePayPalRequest;
use Omnipay\SagePay\Message\DirectPurchaseRequest;
use Omnipay\SagePay\Message\DirectTokenRegistrationRequest;
use Omnipay\SagePay\Message\SharedAbortRequest;
use Omnipay\SagePay\Message\SharedCaptureRequest;
use Omnipay\SagePay\Message\SharedRefundRequest;
use Omnipay\SagePay\Message\SharedRepeatAuthorizeRequest;
use Omnipay\SagePay\Message\SharedRepeatPurchaseRequest;
use Omnipay\SagePay\Message\SharedTokenRemovalRequest;
use Omnipay\SagePay\Message\SharedVoidRequest;

/**
 * Sage Pay Direct Gateway
 */
class DirectGateway extends AbstractGateway
{
    // Gateway identification.

    /**
     * @return string
     */
    public function getName()
    {
        return 'Sage Pay Direct';
    }

    /**
     * Direct methods.
     */

    /**
     * Authorize and handling of return from 3D Secure or PayPal redirection.
     * @param array $parameters
     * @return AbstractRequest
     */
    public function authorize(array $parameters = [])
    {
        return $this->createRequest(DirectAuthorizeRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest
     */
    public function completePayPal(array $parameters = [])
    {
        return $this->createRequest(DirectCompletePayPalRequest::class, $parameters);
    }

    /**
     * Purchase and handling of return from 3D Secure or PayPal redirection.
     * @param array $parameters
     * @return AbstractRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(DirectPurchaseRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest|RequestInterface
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->completeAuthorize($parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest|RequestInterface
     */
    public function completeAuthorize(array $parameters = [])
    {
        return $this->createRequest(DirectCompleteAuthorizeRequest::class, $parameters);
    }

    /**
     * Shared methods (identical for Direct and Server).
     */

    /**
     * Capture an authorization.
     * @param array $parameters
     * @return AbstractRequest
     */
    public function capture(array $parameters = [])
    {
        return $this->createRequest(SharedCaptureRequest::class, $parameters);
    }

    /**
     * Void a paid transaction.
     * @param array $parameters
     * @return AbstractRequest
     */
    public function void(array $parameters = [])
    {
        return $this->createRequest(SharedVoidRequest::class, $parameters);
    }

    /**
     * Abort an authorization.
     * @param array $parameters
     * @return AbstractRequest
     */
    public function abort(array $parameters = [])
    {
        return $this->createRequest(SharedAbortRequest::class, $parameters);
    }

    /**
     * Void a completed (captured) transaction.
     * @param array $parameters
     * @return AbstractRequest
     */
    public function refund(array $parameters = [])
    {
        return $this->createRequest(SharedRefundRequest::class, $parameters);
    }

    /**
     * Create a new authorization against a previous payment.
     * @param array $parameters
     * @return AbstractRequest
     */
    public function repeatAuthorize(array $parameters = [])
    {
        return $this->createRequest(SharedRepeatAuthorizeRequest::class, $parameters);
    }

    /**
     * Create a new purchase against a previous payment.
     * @param array $parameters
     * @return AbstractRequest
     */
    public function repeatPurchase(array $parameters = [])
    {
        return $this->createRequest(SharedRepeatPurchaseRequest::class, $parameters);
    }

    /**
     * Accept card details from a user and return a token, without any
     * authorization against that card.
     * i.e. standalone token creation.
     * Standard Omnipay function.
     * @param array $parameters
     * @return AbstractRequest
     */
    public function createCard(array $parameters = [])
    {
        return $this->registerToken($parameters);
    }

    /**
     * Accept card details from a user and return a token, without any
     * authorization against that card.
     * i.e. standalone token creation.
     * @param array $parameters
     * @return AbstractRequest
     */
    public function registerToken(array $parameters = [])
    {
        return $this->createRequest(DirectTokenRegistrationRequest::class, $parameters);
    }

    /**
     * Remove a card token from the account.
     * Standard Omnipay function.
     * @param array $parameters
     * @return AbstractRequest
     */
    public function deleteCard(array $parameters = [])
    {
        return $this->removeToken($parameters);
    }

    /**
     * Remove a card token from the account.
     * @param array $parameters
     * @return AbstractRequest
     */
    public function removeToken(array $parameters = [])
    {
        return $this->createRequest(SharedTokenRemovalRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @todo Implement @method RequestInterface updateCard(array $options = array())
     */
    public function updateCard(array $parameters = [])
    {
        throw new BadMethodCallException('This method has not been implemented');
    }

    /**
     * @param array $parameters
     * @return AbstractRequest
     * @deprecated use repeatAuthorize() or repeatPurchase()
     */
    public function repeatPayment(array $parameters = [])
    {
        return $this->createRequest(SharedRepeatPurchaseRequest::class, $parameters);
    }
}
