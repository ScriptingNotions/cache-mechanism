<?php

namespace Homework;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;


class PrintfulApi
{
    /**
     * Printful API key
     * @var string
     */
    private $key = '';

    /**
     * Printful API endpoint
     * @var string
     */
    public $url = '';


    /**
     * @param string $key Printful API key
     * @param string $url Printful API endpoint 
     */
    public function __construct($key, $url) 
    {
        $this->url = $url;
        $this->key = base64_encode($key);
    }

    /**
     * POST request on the API
     * @param array $data array body
     */
    public function post($data = [])
    {
        return $this->request('POST', $this->url, $data);
    }


    /**
     * Initialize request 
     * @param string $method POST...
     * @param string $url
     * @param mixed $data
     * @return
     */
    private function request($method, $url, $data)
    {
        $client = new Client();

        try {
            $res = $client->request($method, $url, 
                [
                    'headers' => [
                        'Authorization' => 'Basic ' . $this->key,
                    ], 
                    'json' => $data
                ]);
            
            return $res->getBody(true)->getContents();
       
        }

        catch (ClientException | RequestException $e ) {
            echo $e->getMessage();
        }
    }
}