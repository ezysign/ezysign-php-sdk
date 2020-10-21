<?php
namespace EzySignSdk;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class EzySignHttpClient
{
    public $hostURL;
    public $defaultHeaders;

    public function __construct($hostURL)
    {
        $this->hostURL = $hostURL;
        $this->defaultHeaders = ['Content-Type' => 'application/json'];
        return $this;
    }

    public function GetBaseUrl()
    {

        return $this->hostURL;
    }

    private function client($method,$url, $headers = [],$body){
        $headers = array_merge($this->defaultHeaders, $headers);
        $client = new Client([
            'timeout'  => 2.0,
        ]);
        $requestArr=array(
            'GET'=>new Request('GET', $this->hostURL . $url, $headers),
            'POST'=>new Request('POST', $this->hostURL . $url, $headers, json_encode($body)),
            'PATCH'=>new Request('PATCH', $this->hostURL . $url, $headers, json_encode($body))
        );
        $request = $requestArr[$method];
        $promise = $client->sendAsync($request);

        $results = null;
        try {
            $results = $promise->wait();
        } catch (Exception $e) {
            $results = null;
            error_log(sprintf("\033[34m%s\033[0m", "EzySign " . $e->getMessage()));
        }

        return $results;
    }

    public function get($url, $headers = [])
    {
        return $this->client("GET",$url,$headers,null);
        
    }

    public function post($url, $headers = [], $body)
    {
        return $this->client("POST",$url,$headers,$body);
       
    }

    public function patch($url, $headers = [], $body)
    {

        return $this->client("PATCH",$url,$headers,$body);

    }

}
