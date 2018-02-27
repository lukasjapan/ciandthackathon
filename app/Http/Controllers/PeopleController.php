<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Helper\BMI;
use App\Http\Controllers\Helper\People;
use App\Service\ImageUrlRetrieverService;
use App\Service\BMIListService;
use Illuminate\Support\Facades\Cache;

class PeopleController extends Controller
{
    public $minutes = 120;

    public function show(Request $request)
    {
        $imageRetrieverService = new ImageUrlRetrieverService;
        
        // request
        $url = 'https://swapi.co/api/people/' . $request->id;

        //$bmiPeople = $request->session()->get('bmiList');
        $bmiPeople = BMIListService::makeBMIList();

        $result = $this->searchBMIById($request->id, $bmiPeople);
        if ($result == null) {
            return;
        }

        $peopleHelper = new People();
        $friends = $peopleHelper->getMyFriends($bmiPeople, $result['index']); 

        $body = \file_get_contents($url);
        $response = json_decode($body);

        $friendImageUrls = array_map(function ($people) use ($imageRetrieverService) {
            return $imageRetrieverService->getImage($people['name']);
        }, $friends);

        $imageUrl = $imageRetrieverService->getImage($response->name);

        return view('people', [
            "main" => array(
                'person' => $response,
                'bmi' => $result['bmi'],
                'imageUrl' => $imageUrl
            ),
            "left" => $friends[0],
            "leftImageUrl" => $friendImageUrls[0],

            "right" => $friends[1],
            "rightImageUrl" => $friendImageUrls[1],

        ]);
    }

    protected function searchBMIById($id, $array)
    {
        foreach ($array as $key => $val) {
            if ($val['id'] === $id) {
                return array('bmi' => $val['bmi'], 'index' => $key);
            }
        }
        return null;
    }

    public function showAll(Request $request)
    {
        $imageRetrieverService = new ImageUrlRetrieverService;

        $page = (int)$request->input('page', 1);

        // request
        $url = "https://swapi.co/api/people/?page=$page";

        // didn't figure out how caching works in time....
        $body = \file_get_contents($url);
        $response = json_decode($body);

        // TODO: should preprocess the whole data and store somewhere
        // right now only first result set is taken into account
        // and is saved into session which is not optimal
        // $bmiHelper = new BMI();
        // $bmiPeople = $bmiHelper->makeBMIList($response);
        // $peopleHelper = new People();

        //$sortedPeople = $peopleHelper->sortByBMI($bmiPeople);
        //$request->session()->put('bmiList', $sortedPeople);

        // get images
        $imageUrls = array_map(function ($people) use ($imageRetrieverService) {
            return $imageRetrieverService->getImage($people->name);
        }, $response->results);

        return view('peoplelist', array(
            'response' => $response,
            'page' => $page,
            'imageUrls' => $imageUrls
        ));
    }

    public function listMock()
    {
        return '{
            "count": 87,
            "next": "https://swapi.co/api/people/?page=2",
            "previous": null,
            "results": [
              {
                "name": "Luke Skywalker",
                "height": "172",
                "mass": "77",
                "hair_color": "blond",
                "skin_color": "fair",
                "eye_color": "blue",
                "birth_year": "19BBY",
                "gender": "male",
                "homeworld": "https://swapi.co/api/planets/1/",
                "films": [
                  "https://swapi.co/api/films/2/",
                  "https://swapi.co/api/films/6/",
                  "https://swapi.co/api/films/3/",
                  "https://swapi.co/api/films/1/",
                  "https://swapi.co/api/films/7/"
                ],
                "species": [
                  "https://swapi.co/api/species/1/"
                ],
                "vehicles": [
                  "https://swapi.co/api/vehicles/14/",
                  "https://swapi.co/api/vehicles/30/"
                ],
                "starships": [
                  "https://swapi.co/api/starships/12/",
                  "https://swapi.co/api/starships/22/"
                ],
                "created": "2014-12-09T13:50:51.644000Z",
                "edited": "2014-12-20T21:17:56.891000Z",
                "url": "https://swapi.co/api/people/1/"
              },
              {
                "name": "C-3PO",
                "height": "167",
                "mass": "75",
                "hair_color": "n/a",
                "skin_color": "gold",
                "eye_color": "yellow",
                "birth_year": "112BBY",
                "gender": "n/a",
                "homeworld": "https://swapi.co/api/planets/1/",
                "films": [
                  "https://swapi.co/api/films/2/",
                  "https://swapi.co/api/films/5/",
                  "https://swapi.co/api/films/4/",
                  "https://swapi.co/api/films/6/",
                  "https://swapi.co/api/films/3/",
                  "https://swapi.co/api/films/1/"
                ],
                "species": [
                  "https://swapi.co/api/species/2/"
                ],
                "vehicles": [],
                "starships": [],
                "created": "2014-12-10T15:10:51.357000Z",
                "edited": "2014-12-20T21:17:50.309000Z",
                "url": "https://swapi.co/api/people/2/"
              },
              {
                "name": "R2-D2",
                "height": "96",
                "mass": "32",
                "hair_color": "n/a",
                "skin_color": "white, blue",
                "eye_color": "red",
                "birth_year": "33BBY",
                "gender": "n/a",
                "homeworld": "https://swapi.co/api/planets/8/",
                "films": [
                  "https://swapi.co/api/films/2/",
                  "https://swapi.co/api/films/5/",
                  "https://swapi.co/api/films/4/",
                  "https://swapi.co/api/films/6/",
                  "https://swapi.co/api/films/3/",
                  "https://swapi.co/api/films/1/",
                  "https://swapi.co/api/films/7/"
                ],
                "species": [
                  "https://swapi.co/api/species/2/"
                ],
                "vehicles": [],
                "starships": [],
                "created": "2014-12-10T15:11:50.376000Z",
                "edited": "2014-12-20T21:17:50.311000Z",
                "url": "https://swapi.co/api/people/3/"
              },
              {
                "name": "Darth Vader",
                "height": "202",
                "mass": "136",
                "hair_color": "none",
                "skin_color": "white",
                "eye_color": "yellow",
                "birth_year": "41.9BBY",
                "gender": "male",
                "homeworld": "https://swapi.co/api/planets/1/",
                "films": [
                  "https://swapi.co/api/films/2/",
                  "https://swapi.co/api/films/6/",
                  "https://swapi.co/api/films/3/",
                  "https://swapi.co/api/films/1/"
                ],
                "species": [
                  "https://swapi.co/api/species/1/"
                ],
                "vehicles": [],
                "starships": [
                  "https://swapi.co/api/starships/13/"
                ],
                "created": "2014-12-10T15:18:20.704000Z",
                "edited": "2014-12-20T21:17:50.313000Z",
                "url": "https://swapi.co/api/people/4/"
              },
              {
                "name": "Leia Organa",
                "height": "150",
                "mass": "49",
                "hair_color": "brown",
                "skin_color": "light",
                "eye_color": "brown",
                "birth_year": "19BBY",
                "gender": "female",
                "homeworld": "https://swapi.co/api/planets/2/",
                "films": [
                  "https://swapi.co/api/films/2/",
                  "https://swapi.co/api/films/6/",
                  "https://swapi.co/api/films/3/",
                  "https://swapi.co/api/films/1/",
                  "https://swapi.co/api/films/7/"
                ],
                "species": [
                  "https://swapi.co/api/species/1/"
                ],
                "vehicles": [
                  "https://swapi.co/api/vehicles/30/"
                ],
                "starships": [],
                "created": "2014-12-10T15:20:09.791000Z",
                "edited": "2014-12-20T21:17:50.315000Z",
                "url": "https://swapi.co/api/people/5/"
              },
              {
                "name": "Owen Lars",
                "height": "178",
                "mass": "120",
                "hair_color": "brown, grey",
                "skin_color": "light",
                "eye_color": "blue",
                "birth_year": "52BBY",
                "gender": "male",
                "homeworld": "https://swapi.co/api/planets/1/",
                "films": [
                  "https://swapi.co/api/films/5/",
                  "https://swapi.co/api/films/6/",
                  "https://swapi.co/api/films/1/"
                ],
                "species": [
                  "https://swapi.co/api/species/1/"
                ],
                "vehicles": [],
                "starships": [],
                "created": "2014-12-10T15:52:14.024000Z",
                "edited": "2014-12-20T21:17:50.317000Z",
                "url": "https://swapi.co/api/people/6/"
              },
              {
                "name": "Beru Whitesun lars",
                "height": "165",
                "mass": "75",
                "hair_color": "brown",
                "skin_color": "light",
                "eye_color": "blue",
                "birth_year": "47BBY",
                "gender": "female",
                "homeworld": "https://swapi.co/api/planets/1/",
                "films": [
                  "https://swapi.co/api/films/5/",
                  "https://swapi.co/api/films/6/",
                  "https://swapi.co/api/films/1/"
                ],
                "species": [
                  "https://swapi.co/api/species/1/"
                ],
                "vehicles": [],
                "starships": [],
                "created": "2014-12-10T15:53:41.121000Z",
                "edited": "2014-12-20T21:17:50.319000Z",
                "url": "https://swapi.co/api/people/7/"
              },
              {
                "name": "R5-D4",
                "height": "97",
                "mass": "32",
                "hair_color": "n/a",
                "skin_color": "white, red",
                "eye_color": "red",
                "birth_year": "unknown",
                "gender": "n/a",
                "homeworld": "https://swapi.co/api/planets/1/",
                "films": [
                  "https://swapi.co/api/films/1/"
                ],
                "species": [
                  "https://swapi.co/api/species/2/"
                ],
                "vehicles": [],
                "starships": [],
                "created": "2014-12-10T15:57:50.959000Z",
                "edited": "2014-12-20T21:17:50.321000Z",
                "url": "https://swapi.co/api/people/8/"
              },
              {
                "name": "Biggs Darklighter",
                "height": "183",
                "mass": "84",
                "hair_color": "black",
                "skin_color": "light",
                "eye_color": "brown",
                "birth_year": "24BBY",
                "gender": "male",
                "homeworld": "https://swapi.co/api/planets/1/",
                "films": [
                  "https://swapi.co/api/films/1/"
                ],
                "species": [
                  "https://swapi.co/api/species/1/"
                ],
                "vehicles": [],
                "starships": [
                  "https://swapi.co/api/starships/12/"
                ],
                "created": "2014-12-10T15:59:50.509000Z",
                "edited": "2014-12-20T21:17:50.323000Z",
                "url": "https://swapi.co/api/people/9/"
              },
              {
                "name": "Obi-Wan Kenobi",
                "height": "182",
                "mass": "77",
                "hair_color": "auburn, white",
                "skin_color": "fair",
                "eye_color": "blue-gray",
                "birth_year": "57BBY",
                "gender": "male",
                "homeworld": "https://swapi.co/api/planets/20/",
                "films": [
                  "https://swapi.co/api/films/2/",
                  "https://swapi.co/api/films/5/",
                  "https://swapi.co/api/films/4/",
                  "https://swapi.co/api/films/6/",
                  "https://swapi.co/api/films/3/",
                  "https://swapi.co/api/films/1/"
                ],
                "species": [
                  "https://swapi.co/api/species/1/"
                ],
                "vehicles": [
                  "https://swapi.co/api/vehicles/38/"
                ],
                "starships": [
                  "https://swapi.co/api/starships/48/",
                  "https://swapi.co/api/starships/59/",
                  "https://swapi.co/api/starships/64/",
                  "https://swapi.co/api/starships/65/",
                  "https://swapi.co/api/starships/74/"
                ],
                "created": "2014-12-10T16:16:29.192000Z",
                "edited": "2014-12-20T21:17:50.325000Z",
                "url": "https://swapi.co/api/people/10/"
              }
            ]
          }';
    }

    public function lukeMock()
    {
        return '{
            "name": "Luke Skywalker",
            "height": "172",
            "mass": "77",
            "hair_color": "blond",
            "skin_color": "fair",
            "eye_color": "blue",
            "birth_year": "19BBY",
            "gender": "male",
            "homeworld": "https://swapi.co/api/planets/1/",
            "films": [
              "https://swapi.co/api/films/2/",
              "https://swapi.co/api/films/6/",
              "https://swapi.co/api/films/3/",
              "https://swapi.co/api/films/1/",
              "https://swapi.co/api/films/7/"
            ],
            "species": [
              "https://swapi.co/api/species/1/"
            ],
            "vehicles": [
              "https://swapi.co/api/vehicles/14/",
              "https://swapi.co/api/vehicles/30/"
            ],
            "starships": [
              "https://swapi.co/api/starships/12/",
              "https://swapi.co/api/starships/22/"
            ],
            "created": "2014-12-09T13:50:51.644000Z",
            "edited": "2014-12-20T21:17:56.891000Z",
            "url": "https://swapi.co/api/people/1/"
          }';
    }
}
