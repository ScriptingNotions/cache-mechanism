<?php

declare(strict_types=1);

namespace Homework;

use Homework\PrintfulApi;
use Homework\Cache;


class Application
{
    public function run(): void
    {
        $key = $_ENV['key1'];
        $url = 'https://api.printful.com/shipping/rates';
        
        $printfulApi = new PrintfulApi($key, $url); 
        $cache = new Cache();
        
        $results = null;
        $from = '';
        
        if (!empty($_POST)) {
            $input = $_POST;
            $cacheKey = strtoupper(json_encode($input));
            $getCache = $cache->get($cacheKey);

            if (is_null($getCache)) {
                $results = $printfulApi->post($input);
                $from = "From API";

                $cache->set($cacheKey, $results, 15);
            } else {
                $results = $getCache;
                $from = "From Cache";
            }              

        }

        echo $this->renderView('views/form.php', ['results' => $results, 'from' => $from]); 
    }

    public function renderView(string $filePath, array $variables = []): string
    {
        ob_start();
        extract($variables, EXTR_OVERWRITE);
        include($filePath);

        return ob_get_clean();
    }


}
