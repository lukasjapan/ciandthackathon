<?php

namespace App\Service;

use Illuminate\Support\Facades\Cache;

class ImageUrlRetrieverService {
    public $client = null;

    public function __construct() {
        $this->client = new \Google_Client();
        $this->client->setApplicationName("cithackathonstarwars");
        $this->client->setDeveloperKey('');
    }

    public function getImage($name) {
        return Cache::remember('image' . $name , 60*24, function() use ($name) {
            // return "https://lumiere-a.akamaihd.net/v1/images/luke-skywalker-main_5a38c454_461eebf5.jpeg?region=0%2C0%2C1536%2C864&width=768";

            
            // should cache with laraval but dont know how...

            //$filename = "/tmp/" . md5($name);

            //if(file_exists($filename)) {
            //    return file_get_contents($filename);
            //}

            // just in case for now to save API requests
            // Luke image response

            $service = new \Google_Service_Customsearch($this->client);
            $optParams = array("cx"=>"011611812558793095754:pehunczquj4", "searchType" => "image");    
            $results = $service->cse->listCse($name, $optParams);
            $url = $results->items[0]->link;

            //file_put_contents($filename, $url);

            return $url;
        });
    }
}