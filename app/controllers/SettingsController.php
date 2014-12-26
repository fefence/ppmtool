<?php

class SettingsController extends BaseController{

    public static function displaySettings()
    {
        $user_id = Auth::user()->id;
        $res = Setting::where('user_id', $user_id)->get();
        $data = array();
        foreach($res as $d) {
            if (!array_key_exists($d->league_id, $data)) {
                $data[$d->league_id] = array();
            }
            array_push($data[$d->league_id], $d->game_type_id);
        }
        $leagues = League::where('hidden', 0)
            ->orderBy('country')
            ->get();
        $game_types = GameType::all();

        return View::make('settings')->with(['data' => $data, 'leagues' => $leagues, 'game_types' => $game_types]);
    }

    public static function disableSettings($league_id, $game_type_id) {
        $user_id = Auth::user()->id;
        Setting::disableSeries($league_id, $user_id, $game_type_id);
        return Redirect::back()->with('message', "Settings saved");
    }

    public static function enableSettings($league_id, $game_type_id) {
        $user_id = Auth::user()->id;
        Setting::enableSeries($league_id, $user_id, $game_type_id);
        return Redirect::back()->with('message', "Settings saved");
    }
} 