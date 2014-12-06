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
}