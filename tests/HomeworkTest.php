<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Homework\Cache;
use Homework\PrintfulApi;

class HomeworkTest extends TestCase {
    public function testprintfulApi() {
        $key = '77qn9aax-qrrm-idki:lnh0-fm2nhmp0yca7';
        $url = 'https://api.printful.com/shipping/rates';
        
        $printfulApi = new PrintfulApi($key, $url); 
        $input = [ 
            "recipient" =>
            [ 
              "address1"=> "19749 Dearborn St",
              "city"=> "Chatsworth",
              "state_code"=> "CA",
              "zip"=> "91311",
              "country_code"=> "us"
            ],
            "items" =>
            [ 
              [ 
                "variant_id" => "2",
                "quantity" => "1"
              ],
              [ 
                "variant_id" => "202",
                "quantity" => "5"
              ]
            ]
        ];


        $result = $printfulApi->post($input);
        $jsonResult = json_decode($result);
        $resStatus  = $jsonResult->code;

        $this->assertSame(200, $resStatus);
    }

    public function testCache() {
        $cache = new Cache();
        $setCache = $cache->set('testCache', 'data', 10);

        $this->assertSame(true , is_int($setCache));
    }
}