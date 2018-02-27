<?php

namespace App\Service;

use App\Http\Controllers\Helper\BMI;
use App\Http\Controllers\Helper\People;
use Illuminate\Support\Facades\Cache;

class BMIListService {
    public static function makeBMIList() {
        return Cache::remember('bmilist', 60*24, function() {
            $peoples = [];
            $next = 'https://swapi.co/api/people/';

            while($next) {
                $body = \file_get_contents($next);
                $result = json_decode($body);
                $next = $result->next;
                $peoples = array_merge($peoples, $result->results);
            }

            $result->results = $peoples;

            $bmiHelper = new BMI();
            $bmiPeople = $bmiHelper->makeBMIList($result);
            $peopleHelper = new People();

            $bmiPeople = $peopleHelper->sortByBMI($bmiPeople);

            return $bmiPeople;
        });
    }
}