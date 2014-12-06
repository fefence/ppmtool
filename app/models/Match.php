<?php


class Match extends Eloquent
{
    protected $table = 'matches';
    public $timestamps = false;
    public static $unguarded = true;

    public function league() {
        return $this->belongsTo('League');
    }
//    public static $unguarded = true;

    public static function endSeries($matches, $game) {
        foreach($matches as $match) {
            switch ($game) {
                case '1':
                    if ($match->short_result == 'D')
                        return true;
                    break;
                case '2':
                    if ($match->short_result == 'D' && $match->home_goals == 0 && $match->away_goals == 0)
                        return true;
                    break;
                case '3':
                    if ($match->short_result == 'D' && $match->home_goals == 1 && $match->away_goals == 1)
                        return true;
                    break;
                case '4':
                    if ($match->short_result == 'D' && $match->home_goals == 2 && $match->away_goals == 2)
                        return true;
                    break;
                case '5':
                    if ($match->short_result == 'A' && $match->home_goals == 0 && $match->away_goals == 1)
                        return true;
                    break;
                case '6':
                    if ($match->short_result == 'A' && $match->home_goals == 0 && $match->away_goals == 2)
                        return true;
                    break;
                case '7':
                    if ($match->short_result == 'H' && $match->home_goals == 1 && $match->away_goals == 0)
                        return true;
                    break;
                case '8':
                    if ($match->short_result == 'H' && $match->home_goals == 2 && $match->away_goals == 0)
                        return true;
                    break;
                case '9':
                    if ($match->short_result == 'A' && $match->home_goals == 1 && $match->away_goals == 2)
                        return true;
                    break;
                case '10':
                    if ($match->short_result == 'H' && $match->home_goals == 2 && $match->away_goals == 1)
                        return true;
                    break;
            }
        }

    }

    public static function updated($matches) {
        foreach($matches as $match) {
            if ($match->short_result == '-') {
                return false;
            }
        }
        return true;
    }

    public static function saveMatches($matches) {
        foreach($matches as $match) {
            $match->save();
        }
    }
}