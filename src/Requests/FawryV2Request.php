<?php

namespace SherifAI\LaraFawry\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class FawryV2Request extends FormRequest
{

    public function authorize()
    {
        if ($this->messageSignature != $this->generatedSignature) {
            Log::error("FAWRY ERROR: {$this->messageSignature} <=> {$this->generatedSignature}");
        }
        return $this->messageSignature == $this->generatedSignature;
    }

    public function rules()
    {
        return [];
    }

    protected function prepareForValidation()
    {
        $hashString = $this->fawryRefNumber;
        $hashString .= $this->merchantRefNumber;
        $hashString .= number_format($this->paymentAmount, 2, '.', '');
        $hashString .= number_format($this->orderAmount, 2, '.', '');
        $hashString .= $this->orderStatus;
        $hashString .= $this->paymentMethod;
        $hashString .= $this->paymentRefrenceNumber;
        $hashString .= Config::get('fawry.merchant_secret');

        $generatedSignature = hash("sha256", $hashString);
        
        $this->merge(['generatedSignature' => $generatedSignature]);
    }
}
