<?php

class Updater
{

    public static function update($league_id)
    {
        $m = self::getNextMatches($league_id);
        $matches = Parser::updateMatchesResult($m);
        if (Match::updated($matches)) {
            for ($i = 1; $i < 11; $i++) {
                $series = Series::where('league_id', $league_id)
                    ->where('active', 1)
                    ->where('game_type_id', $i)
                    ->first();
                $nextMatches = self::getNextMatches($league_id);
//                return $matches;
                if (Match::endSeries($matches, $i)) {
                    $series->active = 0;
                    $newSeries = new Series;
                    $newSeries->league_id = $series->league_id;
                    $newSeries->length = 0;
                    $newSeries->start_match_id = $nextMatches[0]->id;
                    $newSeries->end_match_id = $nextMatches[0]->id;
                    $newSeries->game_type_id = $i;
                    $newSeries->active = 1;
                } else {
                    $series->length = $series->length + 1;
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

    public static function getNextMatches($league_id) {
        $next_time = Match::where('short_result', '-')
            ->where('league_id', $league_id)
            ->orderBy('date_time')
            ->first()
            ->date_time;
        $next_matches = Match::where('league_id', $league_id)
            ->where('date_time', $next_time)
            ->get();
        return $next_matches;
    }

    public static function getMatchesToUpdate($league_id) {

    }
} 