<?php


class PPMController extends \BaseController
{

    public static function displaySeries()
    {
        $countries = League::where(Auth::user()->id, 0)
            ->orderBy('country')
            ->lists('id', 'country_alias');
        $games = GameType::all();
        $data = array();
        foreach ($countries as $country => $league_id) {
            for ($i = 1; $i < 11; $i++) {
                $top = '';
                $top_25 = Series::where('country_alias', $country)
                    ->join('leagues', 'leagues.id', '=', 'series.league_id')
                    ->where('game_type_id', $i)
                    ->select(DB::raw('series.id, length'))
                    ->orderBy('length', "desc")
                    ->take(25)
                    ->get();


                $current = Series::where('country_alias', $country)
                    ->join('leagues', 'leagues.id', '=', 'series.league_id')
                    ->join('matches', 'matches.id', '=', 'series.end_match_id')
                    ->where('season', '2014-2015')
                    ->where('game_type_id', $i)
                    ->orderBy('date_time', "asc")
                    ->select(DB::raw('series.id, length'))
                    ->get();
                $c = '';
                foreach ($current as $t) {
                    $c = $c . $t->length . ", ";
                }

                $data[$country][$i] = Series::where('active', 1)
                    ->where('country_alias', $country)
                    ->join('leagues', 'leagues.id', '=', 'series.league_id')
                    ->where('game_type_id', $i)
                    ->select(DB::raw('series.id, length, be, sc, ss'))
                    ->first();

                if (count($data[$country][$i]) == 0) {
                    $data[$country][$i]['length'] = 0;
                    $data[$country][$i]['odds'] = 0;
                    $data[$country][$i]['c_odds'] = 0;
                    $data[$country][$i] = new Series;
                }
                $k = 0;
                foreach ($top_25 as $t) {
                    $k++;
                    if ($current->contains($t->id)) {
                        if ($t->id == $data[$country][$i]->id) {
                            $top = $top . "<span class='bg-danger text-danger' style='font-weight: bold;'>" . $t->length . "</span>, ";
                        } else {
                            $top = $top . "<span class='bg-info text-info' style='font-weight: bold;'>" . $t->length . "</span>, ";
                        }
                    } else {
                        $top = $top . $t->length . ", ";
                    }
                    if ($k > 15) {
                        continue;
                    } else if ($k == 15) {
                        $top = $top . "<span style='color: #aaa;'>";
                    } else {
                        $data[$country][$i]['treshold'] = $t->length;
                    }
                }
                $data[$country][$i]['top'] = substr($top, 0, strlen($top) - 2) . "</span>";
                $data[$country][$i]['curr'] = substr($c, 0, strlen($c) - 2);
                $data[$country][$i]['odds'] = round(WinOdds::join('matches', 'win_odds.match_id', '=', 'matches.id')
                    ->where('league_id', $league_id)
                    ->where('game_type_id', $i)
                    ->select(DB::raw("AVG(odds) as odds"))
                    ->first()->odds, 2, PHP_ROUND_HALF_UP);
                $data[$country][$i]['c_odds'] = WinOdds::join('matches', 'win_odds.match_id', '=', 'matches.id')
                    ->where('league_id', $league_id)
                    ->where('game_type_id', $i)
                    ->select(DB::raw("count(*) as c"))
                    ->first()->c;

            }
        }
//        return $data;
        return View::make('ppm')->with(['data' => $data, 'games' => $games]);
    }

    public static function displaySeriesGames($series_id)
    {
        $user_id = Auth::user()->id;
        $games = Game::where('user_id', $user_id)
            ->join('matches', 'matches.id', '=', 'games.match_id')
            ->where('series_id', $series_id)
            ->where('confirmed', 1)
            ->with('game_type')
            ->select(DB::raw('games.*, matches.home, matches.away, matches.date_time, matches.home_goals, matches.away_goals, matches.short_result'))
            ->orderBy('current_length', "desc")
            ->get();
        if (count($games) > 0) {
            $no_info = false;
        } else {
            $no_info = true;
        }
        $series = Series::find($series_id);
        $ss = Series::where('game_type_id', $series->game_type_id)
            ->where('matches.league_id', $series->league_id)
            ->join('matches', 'matches.id', '=', 'series.end_match_id')
            ->where('season', '2014-2015')
            ->orderBy('date_time')
            ->get();
        $data['stats'] = $ss;
        $longest = Series::where('game_type_id', $series->game_type_id)
            ->where('matches.league_id', $series->league_id)
            ->join('matches', 'matches.id', '=', 'series.end_match_id')
            ->where('season', '2014-2015')
            ->orderBy('length', "desc")
            ->take(5)
            ->lists('length');
        $data['league'] = League::find($series->league_id);
        $data['game_type'] = GameType::find($series->game_type_id);
        $data['longest'] = $longest;
        $top = Series::where('game_type_id', $series->game_type_id)
            ->where('matches.league_id', $series->league_id)
            ->join('matches', 'matches.id', '=', 'series.end_match_id')
            ->orderBy('length', "desc")
            ->take(25)
            ->lists('length');
        $data['all'] = $top;
        $data['avg_odds'] = round(WinOdds::join('matches', 'win_odds.match_id', '=', 'matches.id')
            ->where('league_id', $series->league_id)
            ->where('game_type_id', $series->game_type_id)
            ->select(DB::raw("AVG(odds) as odds"))
            ->first()->odds, 2, PHP_ROUND_HALF_UP);;
//        return $data;
        $data['avg_count'] = WinOdds::join('matches', 'win_odds.match_id', '=', 'matches.id')
            ->where('league_id', $series->league_id)
            ->where('game_type_id', $series->game_type_id)
            ->select(DB::raw("count(*) as c"))
            ->first()->c;
        return View::make('seriesdetails')->with(['games' => $games, 'data' => $data, 'no_info' => $no_info]);
    }
}