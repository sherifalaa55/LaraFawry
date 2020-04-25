<?php

namespace SherifAI\LaraFawry\Requests;

use Illuminate\Support\Facades\Config;

class ChargeRequestBuilder
{
	
	protected $merchantCode;
	protected $merchantSecret;
	protected $merchantRefNum;
	protected $cardToken;
	protected $customerMobile;
	protected $customerProfileId;
	protected $customerEmail;
	protected $paymentMethod;
	protected $paymentExpiry;
	protected $amount;
	protected $chargeItems = [];

	function __construct()
	{
		$this->merchantCode = Config::get('fawry.merchant_code');
		$this->merchantSecret = Config::get('fawry.merchant_secret');
	}

	public function setmerchantRefNum($merchantRefNum)
	{
		$this->merchantRefNum = $merchantRefNum;

		return $this;
	}

	public function setCustomerProfileId($customerProfileId)
	{
		$this->customerProfileId = $customerProfileId;

		return $this;
	}

	public function setCardToken($cardToken)
	{
		$this->cardToken = $cardToken;

		return $this;
	}

	public function setCustomerMobile($customerMobile)
	{
		$this->customerMobile = $customerMobile;

		return $this;
	}

	public function setCustomerEmail($customerEmail)
	{
		$this->customerEmail = $customerEmail;

		return $this;
	}

	public function setPaymentMethod($paymentMethod)
	{
		$this->paymentMethod = $paymentMethod;

		return $this;
	}

	public function setPaymentExpiry($paymentExpiry)
	{
		$this->paymentExpiry = $paymentExpiry;

		return $this;
	}

	public function addItem($itemId, $price, $quantity)
	{
		$this->chargeItems[] = [
			"itemId" => $itemId,
			"price" => number_format($price, 2, '.', ''),
			"quantity" => $quantity
		];

		return $this;
	}

	public function removeItem($itemId)
	{
		foreach ($this->chargeItems as $key => $item) {
			if ($item['itemId'] == $itemId) {
				unset($this->chargeItems[$key]);
			}
		}
	}

	public function setAmount($amount)
	{
		$this->amount = number_format($amount, 2, '.', '');

		return $this;
	}

	/**
	 * Generates hash signature
	 */
	private function generateSignature()
	{
		// validations and exceptions

		$hashString = $this->merchantCode . $this->merchantRefNum . $this->customerProfileId . $this->paymentMethod . number_format($this->amount, 2, '.', '') . $this->cardToken . $this->merchantSecret;

		$this->signature = hash('sha256', $hashString);

		return $this->signature;
	}

	public function build()
	{
		$chargeRequest = new ChargeRequest;
		$chargeRequest->merchantCode = $this->merchantCode;
		$chargeRequest->merchantRefNum = $this->merchantRefNum;
		$chargeRequest->customerProfileId = $this->customerProfileId;
		$chargeRequest->customerEmail = $this->customerEmail;
		$chargeRequest->customerMobile = $this->customerMobile;
		$chargeRequest->paymentMethod = $this->paymentMethod;
		$chargeRequest->amount = $this->amount;
		$chargeRequest->chargeItems = $this->chargeItems;
		$chargeRequest->paymentExpiry = $this->paymentExpiry;
		$chargeRequest->cardToken = $this->cardToken;
		$chargeRequest->signature = $this->generateSignature();

		return $chargeRequest;
	}
}