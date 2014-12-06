<?php

class Setting extends Eloquent{
    protected $table = 'settings';
    public static $unguarded = true;

    public function league() {
        return $this->belongsTo('League');
    }

    public static function enableSeries($league_id, $user_id, $game_type_id) {
        $settings = Setting::create(['user_id' => $user_id, 'league_id' => $league_id, 'game_type_id' => $game_type_id]);
        $settings->save();
        $matches = Updater::getMatchesToUpdate($league_id);
        $series = Series::where('league_id', $league_id)
            ->where('game_type_id', $game_type_id)
            ->where('active', 1)
            ->first();
        foreach ($matches as $m) {
            $game = Game::firstOrCreate(['user_id' => $user_id, 'match_id' => $m->id, 'game_type_id' => $game_type_id, 'series_id' => $series->id, 'current_length' => $series->length]);
            $game->odds = Parser::getOdds($m->id)[$game_type_id];
            $game->save();
        }
        ActionLog::create(['user_id' => $user_id, 'league_id' => $league_id, 'type' => 'settings', 'action' => 'enable', 'description' => 'Enable settings and create games']);
    }

    public static function disableSeries($league_id, $user_id, $game_type_id) {
        $settings = Setting::where('user_id', $user_id)
            ->where('league_id', $league_id)
            ->where('game_type_id', $game_type_id)
            ->first();
        $settings->delete();
        $matches = Updater::getMatchesToUpdate($league_id);
        foreach ($matches as $m) {
            $games = Game::where('user_id', $user_id)
                ->where('game_type_id', $game_type_id)
                ->where('match_id', $m->id)
                ->get();
            foreach($games as $game) {
                $game->delete();
            }
        }
        ActionLog::create(['user_id' => $user_id, 'league_id' => $league_id, 'type' => 'settings', 'action' => 'disable', 'description' => 'Disable settings and remove games']);
    }
}