<?php

namespace App\Http\Controllers\Helper;

class BMI 
{
    public function makeBMIList($people)
    {
        $bmiPeople = array();
        foreach($people->results as $person)
        {
            if(!is_numeric($person->height)) continue;
            if(!is_numeric($person->mass)) continue;

            $bmi = $this->calBMI($person);
            $bmiPerson['name'] = $person->name;
            $bmiPerson['id'] = $this->extractId($person->url);
            $bmiPerson['bmi'] = $bmi;

            array_push($bmiPeople, $bmiPerson);
        }

        return $bmiPeople;
    }
    public function calBMI($person){
        $bmi = ($person->mass / ($person->height* 0.01)**2);
        return $bmi;
    }

    protected function extractId($url)
    {
        $elems = explode('/', $url);
        return $elems[count($elems) - 2];
    }
}
