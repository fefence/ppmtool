<?php

class Updater
{

    public static function update($league_id)
    {
        $m = self::getMatchesToUpdate($league_id);

        $matches = Parser::updateMatchesResult($m);
        if (Match::updated($matches)) {
            Parser::parseNextMatches($league_id);
            Parser::parseNextMatches($league_id);
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
                    self::updateGamesEndSeries($league_id, $i, $matches);
                } else {
                    $series->length = $series->length + count($matches);
                    $series->end_match_id = $nextMatches->last()->id;
                    $newSeries = $series;
                    self::updateGamesNotEndSeries($league_id, $i, $matches, $nextMatches, $series);
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

    public static function getNextMatches($matches)
    {
        $time = $matches->last()->date_time;

        $next_time = Match::where('league_id', $matches->last()->league_id)
            ->where('date_time', '>', $time)
            ->where('state', '<>', 'POSTP.')
            ->orderBy('date_time')
            ->first()
            ->date_time;
        $next_matches = Match::where('league_id', $matches->last()->league_id)
            ->where('date_time', $next_time)
            ->get();
        return $next_matches;
    }

    public static function getMatchesToUpdate($league_id)
    {
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

    public static function updateGamesEndSeries($league_id, $game_type_id, $matches)
    {
        $user_settings = Setting::where('league_id', $league_id)
            ->where('game_type_id', $game_type_id)
            ->get();
        foreach ($user_settings as $settings) {
            $user = User::find($settings->user_id);

            foreach ($matches as $match) {
                $confirmed = Game::where('confirmed', 1)
//                    ->where('league_id', $league_id)
                    ->where('game_type_id', $game_type_id)
                    ->where('user_id', $user->id)
                    ->where('match_id', $match->id)
                    ->get();
                if (Match::endSeries([$match], $game_type_id)) {
                    if (count($confirmed) > 0) {
                        foreach ($confirmed as $conf) {
                            $user->account = $user->account + $conf->bet * $conf->odds;
                            $user->save();
                        }
                    }
                }
            }
        }
        foreach ($user_settings as $user_setting) {
            $user_setting->delete();
        }
    }

    public static function updateGamesNotEndSeries($league_id, $game_type_id, $matches, $next_matches, $series)
    {
        $user_settings = Setting::where('league_id', $league_id)
            ->where('game_type_id', $game_type_id)
            ->get();
        foreach ($user_settings as $settings) {
            $bsf = 0;
            foreach ($matches as $match) {
                $confirmed = Game::where('confirmed', 1)
                    ->where('game_type_id', $game_type_id)
                    ->where('user_id', $settings->user_id)
                    ->where('match_id', $match->id)
                    ->get();
                $not_confirmed = Game::where('confirmed', 0)
                    ->where('game_type_id', $game_type_id)
                    ->where('user_id', $settings->user_id)
                    ->where('match_id', $match->id)
                    ->first();
//                return $not_confirmed;
                $bsf = 0;
                if (count($confirmed) == 0) {
                    $bsf = $not_confirmed->bsf;
                } else {
                    foreach ($confirmed as $conf) {
                        $bsf = $bsf + $conf->bet + $conf->bsf;
                    }
                }
            }
            $bsfpm = $bsf / count($next_matches);
            $body = "";
            foreach ($next_matches as $next_match) {
                $game = new Game;
                $game->bsf = $bsfpm;
                $game->match_id = $next_match->id;
                $game->user_id = $settings->user_id;
                $game->game_type_id = $game_type_id;
                $game->current_length = $series->length;
                $game->series_id = $series->id;
                $odds = Parser::getOdds($next_match->id)[$game_type_id];
                if ($odds != null && $odds != -1) {
                    $game->odds = $odds;
                    $body = $body." ".$next_match->home." - ".$next_match->away." ".GameType::find($game_type_id)->name." [".$series->length."] <br>";
                }
                $game->save();
            }
            if ($body != "") {
                Sender::sendMail(User::find($settings->user_id), "games to confirm", $body);
            }
        }
    }
} 