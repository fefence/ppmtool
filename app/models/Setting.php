<?php

class Setting extends Eloquent{
    protected $table = 'settings';
    public static $unguarded = true;

    public function league() {
        return $this->belongsTo('League');
    }

    public static function enableSeries($league_id, $user_id, $game_type_id) {
        $settings = Setting::firstOrNew(['user_id' => $user_id, 'league_id' => $league_id, 'game_type_id' => $game_type_id]);
        $settings->save();
        $matches = Updater::getMatchesToUpdate($league_id);
        $series = Series::where('league_id', $league_id)
            ->where('game_type_id', $game_type_id)
            ->where('active', 1)
            ->first();
        foreach ($matches as $m) {
            $game = new Game;
            $game->user_id = $user_id;
            $game->match_id = $m->id;
            $game->game_type_id = $game_type_id;
            $game->series_id = $series->id;
            $game->current_length = $series->length;
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