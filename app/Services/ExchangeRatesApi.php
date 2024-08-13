<?php
//
//namespace App\Services;
//
//use Cache;
//use Illuminate\Support\Facades\Http;
//
//class ExchangeRatesApi
//{
//    protected string $apiUrl = "https://www.cbar.az/currencies/";
//
//    public function __construct()
//    {
//        $this->apiUrl = $this->apiUrl . date('d.m.Y') . ".xml";
//    }
//
//    public function convert($from, $to = 'AZN', $value = 1): float
//    {
//        $data = Cache::remember('exchange_rates', 3600, function (){
//            $XmlResponse = Http::get($this->apiUrl)->body();
//            $xmlToObject = json_encode(simplexml_load_string($XmlResponse, 'SimpleXMLElement', LIBXML_NOCDATA));
//
//            return json_decode($xmlToObject, true)['ValType'][1]['Valute'];
//        });
//
//        $currencies = [];
//
//        foreach ($data as $curr) {
//            if (!in_array($curr['@attributes']['Code'], [$from, $to])){
//                continue;
//            }
//            $currencies[$curr['@attributes']['Code']] = $curr['Value'];
//        }
//        if($from === 'AZN' && $to === 'AZN'){
//            $convertedValue = (float) $value;
//        }else{
//            if ($to === "AZN") {
//                $convertedValue = (float) $value * $currencies[$from];
//            }
//            elseif($from === "AZN") {
//                $convertedValue = (float) $value / $currencies[$to];
//            }
//            else{
//                $convertedValue = (float) $value * ($currencies[$from] / $currencies[$to]);
//            }
//        }
//
//        return round($convertedValue, 2);
//    }
//}