<?php

namespace App\Http\Controllers\Helper;

class People
{

    public function sortByBMI($people)
    {
        usort($people, function ($p1, $p2) {
            if ($p1['bmi'] == $p2['bmi']) {
                return 0;
            }
            return ($p1['bmi'] < $p2['bmi']) ? -1 : 1;
        });

        return $people;
    }

    public function devideByGender($people)
    {
        $bmiPeople['bmiMale'] = array();
        $bmiPeople['bmiFemale'] = array();

        if (strcasecmp($person->gender, "male")) {
            array_push($bmiPeople['bmiMale'], $bmiPerson);
                //array_push($bmiMale, $person);
        } else {
            array_push($bmiPeople['bmiFemale'], $bmiPerson);
                //array_push($bmiFemale, $person);
        }
        return array('male' => $males, 'female' => $females);
    }

    public function getRandomPerson($people, $from, $to)
    {
        $index = rand($from, $to);

        return $people[$index];
    }

    public function getBetterBMIPerson($people, $index)
    {
        $lastIndex = count($people) - 1;
        if ($index == $lastIndex) {
            return $this->getWorseBMIPerson($people, $index);
        }

        return $this->getRandomPerson($people, $index + 1, $lastIndex);
    }

    public function getWorseBMIPerson($people, $index)
    {
        if ($index == 0) {
            return $this->getBetterBMIPerson($people, $index);
        }

        return $this->getRandomPerson($people, 0, $index - 1);
    }

    public function getMyFriends($people, $index)
    {
        $friends = array();
        array_push($friends, $this->getWorseBMIPerson($people, $index));
        array_push($friends, $this->getBetterBMIPerson($people, $index));

        return $friends;
    }
}