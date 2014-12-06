<?php

class SettingsController extends BaseController{

    public static function displaySettings()
    {
        $user_id = Auth::user()->id;
        $data = Setting::where('user_id', $user_id)
            ->lists('game_type_id', 'league_id');
        $leagues = League::all();
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