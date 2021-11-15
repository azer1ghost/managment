<?php

namespace App\Services;

use Cache;
use Illuminate\Support\Facades\Http;

class ExchangeRatesApi
{
    protected string $apiUrl = "https://www.cbar.az/currencies/";

    public function __construct()
    {
        $this->apiUrl = $this->apiUrl . date('d.m.Y') . ".xml";
    }

    public function convert($from, $to = 'AZN', $value = 1): float
    {
       if (Cache::has('exchange_rates')) {
           $data = Cache::get('exchange_rates');
       } else {
           $XmlResponse = Http::get($this->apiUrl)->body();
           $xmlToObject = json_encode(simplexml_load_string($XmlResponse, 'SimpleXMLElement', LIBXML_NOCDATA));
           $data = json_decode($xmlToObject, true)['ValType'][1]['Valute'];
           Cache::put('exchange_rates', $data, 720); //12 hours
       }

        $currencies = [];

        foreach ($data as $curr) {
            if (!in_array($curr['@attributes']['Code'], [$from, $to])){
                continue;
            }
            $currencies[$curr['@attributes']['Code']] = $curr['Value'];
        }
        if($from === 'AZN' && $to === 'AZN'){
            $convertedValue = 1;
        }else{
            if ($to === "AZN") {
                $convertedValue = $value * $currencies[$from];
            }
            elseif($from === "AZN") {
                $convertedValue = $value / $currencies[$to];
            }
            else{
                $convertedValue =  $value * ($currencies[$from] / $currencies[$to]);
            }
        }

        return round($convertedValue, 2);

    }
}