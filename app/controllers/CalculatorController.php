<?php

class CalculatorController extends \BaseController{
    public static function countries(){
        $all_leagues = League::orderBy('country')->get();
        $count = Session::get('count');
        for($i = 0; $i < $count; $i ++) {
            Session::forget($i);
            Session::forget($i."_bet");
            Session::forget($i."_odds");
            Session::forget($i."_bsf");
            Session::forget($i."_income");
            Session::forget($i."_profit");
        }
        Session::forget('game');
        Session::forget('count');
        return View::make('calculatormain')->with(['leagues' => $all_leagues]);
    }

    public static function country($country) {
        $game_types = GameType::all();
        $league = League::where('country', $country)
            ->first();
        $matches = Match::where('league_id', $league->id)
            ->where('short_result', '-')
            ->orderBy('date_time')
            ->get();
        Session::put('count', count($matches));
        return View::make('calculator')->with(['country' =>$league->id, 'matches' => $matches, 'game_types' => $game_types]);
    }

    public static function getOdds($league_id, $game_type_id) {
        $game = GameType::find($game_type_id);
        $count = Session::get('count');
        for($i = 0; $i < $count; $i ++) {
            Session::forget($i);
            Session::forget($i."_bet");
            Session::forget($i."_odds");
            Session::forget($i."_bsf");
            Session::forget($i."_income");
            Session::forget($i."_profit");
        }
        Session::forget('game');
        Session::forget('count');
        Session::put('game', $game->name);
        $matches = Match::where('league_id', $league_id)
            ->where('short_result', '-')
            ->orderBy('date_time')
            ->get();
        $i = 0;
        foreach ($matches as $match) {
            $odds = Parser::getOdds($match->id);
            Session::put($i, $match->id);
            Session::put($i."_odds", $odds[$game_type_id]);
            $i ++;
        }
        Session::put('count', count($matches));
        return Redirect::back();
    }

    public static function calculate() {
        $id = Input::get('id');
        $value = Input::get('value');

        $arr = explode('_', $id);
        $i = $arr[0];
        if ($arr[1] == 'bsf') {
            Session::put($i.'_bsf', $value);
            Session::put($i.'_bet', round($value/(Session::get($i."_odds")-1), 0, PHP_ROUND_HALF_UP));
            Session::put($i.'_income', Session::get($i."_bet")*Session::get($i."_odds"));
            Session::put($i.'_profit', round(Session::get($i."_income")- Session::get($i."_bet")- Session::get($i."_bsf"), 2, PHP_ROUND_HALF_UP));
            $k = $i + 1;
        } else if ($arr[1] == 'bet') {
            Session::put($i.'_bet', $value);
            Session::put(($i + 1).'_bsf', $value + Session::get($i.'_bsf'));
            Session::put(($i + 1).'_bet', round($value/(Session::get(($i + 1)."_odds")-1), 0, PHP_ROUND_HALF_UP));
            Session::put(($i + 1).'_income', Session::get(($i + 1)."_bet")*Session::get(($i + 1)."_odds"));
            Session::put(($i + 1).'_profit', round(Session::get(($i + 1)."_income")- Session::get(($i + 1)."_bet")- Session::get(($i + 1)."_bsf"), 2, PHP_ROUND_HALF_UP));
            $k = $i + 2;
        } else if($arr[1] =='odds') {
            Session::put($i.'_odds', $value);
            Session::put($i.'_bet', round(Session::get($i."_bsf")/(Session::get($i."_odds")-1), 0, PHP_ROUND_HALF_UP));
            Session::put($i.'_income', Session::get($i."_bet")*Session::get($i."_odds"));
            Session::put($i.'_profit', round(Session::get($i."_income")- Session::get($i."_bet")- Session::get($i."_bsf"), 2, PHP_ROUND_HALF_UP));
            $k = $i + 1;
        }
        for($j = $k; $j < Session::get('count'); $j ++) {
            Session::put($j.'_bsf', Session::get(($j - 1)."_bet") + Session::get(($j - 1)."_bsf"));
            Session::put($j.'_bet', round(Session::get($j."_bsf")/(Session::get($j."_odds")-1), 0, PHP_ROUND_HALF_UP));
            Session::put($j.'_income', Session::get($j."_bet")*Session::get($j."_odds"));
            Session::put($j.'_profit', round(Session::get($j."_income")- Session::get($j."_bet")- Session::get($j."_bsf"), 2, PHP_ROUND_HALF_UP));
        }
        return;
    }
} 