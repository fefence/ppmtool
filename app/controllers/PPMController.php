<?php


class PPMController extends \BaseController{

    public static function displaySeries() {
        $countries = League::all()->lists('country');
        $games = GameType::all();
        $data = array();
        foreach($countries as $country) {
            $data[$country] = League::where('active', 1)
                ->where('country', $country)
                ->join('series', 'leagues.id', '=', 'series.league_id')
                ->orderBy('game_type_id')
                ->get();
        }
//        return $data;
        return View::make('ppm')->with(['data' => $data, 'games' => $games]);
    }
}