<?php

class Game extends Eloquent{
    protected $table = 'games';
    public static $unguarded = true;

    public function match() {
        return $this->belongsTo('Match');
    }

    public function game_type() {
        return $this->belongsTo('GameType');
    }

    public function getDates() {
        return ['date_time'];
    }

    public static function confirm($game_id) {
        $game = Game::find($game_id);
        $nGame = $game->replicate();
        $user = User::find($game->user_id);
        $user->account = $user->account - $game->bet;
        $user->save();
        $game->confirmed = 1;
        $game->save();
        $nGame->save();

    }
}