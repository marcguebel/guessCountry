<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Border;
use App\Models\Daily;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{

    public function home(Request $request){
        return view('home', ['countrys' => Country::all()->where('independent', true)]);
    }

    /**
     * Test hello route
     */
    public function hello(Request $request){
        return 'Hello '.$request->route('name').' !';
    }

    /**
     * Play a round
     */
    public function try(Request $request){
        //check if daily country is set
        $daily = Daily::where('created_at', 'like', '%'. date('Y-m-d') .'%')->first();

        if($daily==null)
           $this->createDaily();
        else{



            //get daily country
            $dailyCountry = Country::where('id', $daily->country_id)->first();

            //get country post
            $postCountry = Country::where('name', $request->input('country'))->first();

            //build return
            $json=[
                'country' => $postCountry->name,
                'flag' => $postCountry->flag
            ];

            //check if win
            if($postCountry->id == $daily->country_id){
                $json['status'] = 'win';
                return response()->json($json);
            }
        
            //check if his border
            $border = DB::table('borders')->where([
                ['country_source', $daily->country_id],
                ['country_border', $postCountry->id]
            ])->first();

            if($border != null){
                $json['status'] = 'border';
                return response()->json($json);
            }

            //get ditance 
            $dist = $this->getDistance($dailyCountry->lat, $dailyCountry->lng, $postCountry->lat, $postCountry->lng);
            $json['status'] = $dist;
            return response()->json($json);
        }
    }

    /**
     * Get distance beetwen country post and today country
     */
    public function getDistance($lat1, $lon1, $lat2, $lon2) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return round(($miles * 1.609344));
    }

    /*
     * Generate a daily country
     */
    public function createDaily(){
        $country = Country::all()->where('independent', true)->random(1)->first();
        $daily = new Daily;
        $daily->country_id = $country->id;
        $daily->save();
    }

    /*
     * Scrapping restcountries to get all country and all border associate
     */
    public function scrap(Request $request){
        $search = $request->input('country');

        $url = 'https://restcountries.com/v3.1/all';
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        //part 1 insert des country
        // foreach($data as $line){
        //     $country = new Country;
        //     $country->name = $line['translations']['fra']['common'];
        //     $country->nameOfficial = $line['translations']['fra']['official'];
        //     $country->cca3 = $line['cca3'];
        //     $country->flag = $line['flag'];
        //     $country->lat = $line['latlng'][0];
        //     $country->lng = $line['latlng'][1];
        //     $country->independent = (isset($line['independent']) ? $line['independent'] : 0);
        //     $country->save();
        // }

        //part 2 insert des borders 
        foreach($data as $line){
            // $countrySource = Country::where('cca3', $line['cca3'])->update(['flag' => $line['flag']]);
            // if(isset($line['borders']) && count($line['borders'])>0){
            //     foreach($line['borders'] as $lineBorder){
            //         $countryBorder = DB::table('countries')->where('cca3', $lineBorder)->first();
            //         $border = new Border();
            //         $border->country_source = $countrySource->id;
            //         $border->country_border = $countryBorder->id;
            //         $border->save();
            //     }
            // }
        }

        return response()->json(['success'=>'ok']);
    }
}
