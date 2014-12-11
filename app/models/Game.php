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

    public static function confirmGame($game_id) {
        $game = Game::find($game_id);
        $nGame = $game->replicate();
        $user = User::find($game->user_id);
        $user->account = $user->account - $game->bet;
        $user->save();
        $game->confirmed = 1;
        $game->save();
        $nGame->save();
        $match = Match::find($game->match_id);
        ActionLog::create(['user_id' => $game->user_id, 'league_id' => $match->league_id, 'type' => 'games', 'action' => 'confirm', 'description' => 'Confirm game for match '.$match->home." - ".$match->away." ".$match->date_time]);

    }

    public static function deleteGame($game_id) {
        $game = Game::find($game_id);
        $user = User::find($game->user_id);
        $user->account = $user->account + $game->bet;
        $user->save();
        $match = Match::find($game->match_id);
        ActionLog::create(['user_id' => $game->user_id, 'league_id' => $match->league_id, 'type' => 'games', 'action' => 'delete', 'description' => 'Delete game for match '.$match->home." - ".$match->away." ".$match->date_time]);
        $game->delete();
    }
}