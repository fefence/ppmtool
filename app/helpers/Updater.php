<?php

class Updater
{

    public static function update($league_id)
    {
        $m = self::getMatchesToUpdate($league_id);
        $matches = Parser::updateMatchesResult($m);
        if (Match::updated($matches)) {
            for ($i = 1; $i < 11; $i++) {
                $series = Series::where('league_id', $league_id)
                    ->where('active', 1)
                    ->where('game_type_id', $i)
                    ->first();
                $nextMatches = self::getNextMatches($matches);
//                return $matches;
                if (Match::endSeries($matches, $i)) {
                    $series->active = 0;
                    $newSeries = new Series;
                    $newSeries->league_id = $series->league_id;
                    $newSeries->length = 0;
                    $newSeries->start_match_id = $nextMatches->last()->id;
                    $newSeries->end_match_id = $nextMatches->last()->id;
                    $newSeries->game_type_id = $i;
                    $newSeries->active = 1;
                } else {
                    $series->length = $series->length + 1;
                    $series->end_match_id = $nextMatches->last()->id;
                    $newSeries = $series;
                }
                try {
                    $newSeries->save();
                    $series->save();
                    Match::saveMatches($matches);
                } catch (ErrorException $e) {
                    return $e;
                }
            }

        }
    }

    public static function getNextMatches($matches) {
        $time = $matches->last()->date_time;
        $next_time = Match::where('league_id', $matches->last()->league_id)
            ->where('date_time', '>', $time)
            ->orderBy('date_time')
            ->first()
            ->date_time;
        $next_matches = Match::where('league_id', $matches->last()->league_id)
            ->where('date_time', $next_time)
            ->get();
        return $next_matches;
    }

    public static function getMatchesToUpdate($league_id) {
        $end_match_id = Series::where('active', 1)
            ->where('league_id', $league_id)
            ->first()
            ->end_match_id;
        $next_time = Match::find($end_match_id)
            ->date_time;
        $next_matches = Match::where('league_id', $league_id)
            ->where('date_time', $next_time)
            ->get();
        return $next_matches;
    }
} 