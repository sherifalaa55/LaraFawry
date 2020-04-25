<?php 

namespace SherifAI\LaraFawry;

use Illuminate\Support\Facades\Config;
use SherifAI\LaraFawry\Requests\ChargeRequest;

class Fawry
{
	private $baseUrl;

	public function __construct()
	{
		if (Config::get('fawry.mode') == 'sandbox') {
			$this->baseUrl = 'https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/';
		} elseif (Config::get('fawry.mode') == 'production') {
			$this->baseUrl = 'https://www.atfawry.com/ECommerceWeb/Fawry/';
		}
	}


	public function charge(ChargeRequest $request)
	{
		$ch = curl_init($this->baseUrl . "/payments/charge");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($request))
        ]);

        $result = json_decode(curl_exec($ch));

        return $result;
	}
}
