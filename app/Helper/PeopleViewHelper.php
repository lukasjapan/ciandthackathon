<?php

namespace App\Helper;

class PeopleViewHelper {
    public static function getId($person) {
        // very hacky... no time :P 
        $parts = \explode('/', $person->url);
        \array_pop($parts);
        return \array_pop($parts);
    }
}