<?php


class Series extends Eloquent
{
    protected $table = 'series';
    public static $unguarded = true;

    public static function calculateSeries($league_id, $season)
    {
//        $leagues = LeagueDetails::where('id', '=', $league_id)->get();
//        foreach ($leagues as $league) {
        $matches = Match::where('league_id', '=', $league_id)
            ->where('season', '=', $season)
            ->orderBy('date_time', 'asc')
            ->get(array('id', 'short_result', 'home', 'away', 'date_time', 'home_goals', 'away_goals'));
        foreach ($matches as $match) {
            echo $match->id . "<br>";
            for ($i = 1; $i < 11; $i++) {
                $series = Series::where('league_id', '=', $league_id)->where('active', '=', 1)->where('game_type_id', '=', $i)->first();
                if ($series == NULL) {
                    $series = new Series;
                    $series->league_id = $league_id;
                    $series->game_type_id = $i;
                    $series->length = 0;
                    $series->start_match_id = $match->id;
                    $series->active = 1;
                    $series->save();
                }
                $series->end_match_id = $match->id;
                if (Match::endSeries([$match], $i)) {
                    $series->active = 0;
                    $duplicate = Series::where('start_match_id', '=', $series->start_match_id)
                        ->where('end_match_id', '=', $series->end_match_id)
                        ->where('league_id', '=', $league_id)
                        ->where('length', '=', $series->length)
                        ->where('game_type_id', '=', $series->game_type_id)->first();
                    if ($duplicate) {
                        $duplicate->delete();
                    }
                } else {
                    $series->length = $series->length + 1;
                }
                $series->save();
            }
            if ($match->short_result == '-')
                break 1;
        }
//        }
    }
}