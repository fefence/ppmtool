<?php

class OddsParser
{
    public static function parseOdds($match)
    {
//        $match = Match::find($match_id);

        $league = League::find($match->league_id);
        $baseUrl = "http://www.oddsportal.com/soccer/";
        $url = $baseUrl . $league->country . "/" . $league->name . "/" . $match->id;
        $data = file_get_contents($url);
        $matches = array();
        preg_match('/xhash":"(?P<hash>[a-z0-9-A-Z]+)","/', $data, $matches);
        $hash = $matches['hash'];
        $parse_url = "http://fb.oddsportal.com/feed/match/1-1-" . $match->id . "-8-2-" . $hash . ".dat";
        $json_data = file_get_contents($parse_url);
        $matches2 = array();
        preg_match('/.dat\', (?P<json>.*)\)/', $json_data, $matches2);
        $odds_arr = json_decode($matches2['json'], true);
        try {
            if (Match::endSeries([$match], 1)) {
                $url = "http://www.betexplorer.com/gres/ajax-matchodds.php?t=n&e=" . $match->id . "&b=1x2";
                $data = json_decode(file_get_contents($url))->odds;
                $dom = new domDocument;

                @$dom->loadHTML($data);
                $dom->preserveWhiteSpace = false;
                $table = $dom->getElementById('sortable-1');
                if ($table != null) {
                    $rows = $table->getElementsByTagName('tr');
                    for ($i = 0; $i < $rows->length; $i++) {
                        $row = $rows->item($i);
                        $cols = $row->getElementsByTagName('td');
                        if ($cols->length > 3) {
                            $oddsX = $cols->item(2)->getAttribute("data-odd");
                            $h = $row->getElementsByTagName('th');
                            foreach ($h as $h1) {
                                if (strpos($h1->nodeValue, 'bet365')) {
                                    $win = WinOdds::firstOrCreate(['odds' => $oddsX, 'match_id' => $match->id, 'game_type_id' => 1]);
                                    $win->save();
                                    break 1;
                                }
                            }
                        }
                    }
                }
            }
            if (Match::endSeries([$match], 2)) {
                $odds = $odds_arr['d']['oddsdata']["back"]['E-8-2-0-0-1']['odds'][16][0];
                $game_type_id = 2;
            } else
            if (Match::endSeries([$match], 3)) {
                $odds = $odds_arr['d']['oddsdata']["back"]['E-8-2-0-0-3']['odds'][16][0];
                $game_type_id = 3;
            } else
            if (Match::endSeries([$match], 4)) {
                $odds = $odds_arr['d']['oddsdata']["back"]['E-8-2-0-0-7']['odds'][16][0];
                $game_type_id = 4;
            } else
            if (Match::endSeries([$match], 5)) {
                $odds = $odds_arr['d']['oddsdata']["back"]['E-8-2-0-0-4']['odds'][16][0];
                $game_type_id = 5;
            } else
            if (Match::endSeries([$match], 6)) {
                $odds = $odds_arr['d']['oddsdata']["back"]['E-8-2-0-0-9']['odds'][16][0];
                $game_type_id = 6;
            } else
            if (Match::endSeries([$match], 7)) {
                $odds = $odds_arr['d']['oddsdata']["back"]['E-8-2-0-0-2']['odds'][16][0];
                $game_type_id = 7;
            } else
            if (Match::endSeries([$match], 8)) {
                $odds = $odds_arr['d']['oddsdata']["back"]['E-8-2-0-0-5']['odds'][16][0];
                $game_type_id = 8;
            } else
            if (Match::endSeries([$match], 9)) {
                $odds = $odds_arr['d']['oddsdata']["back"]['E-8-2-0-0-8']['odds'][16][0];
                $game_type_id = 9;
            } else
            if (Match::endSeries([$match], 10)) {
                $odds = $odds_arr['d']['oddsdata']["back"]['E-8-2-0-0-6']['odds'][16][0];
                $game_type_id = 10;
            } else {
                $odds = -1;
                $game_type_id = 0;
            }
        } catch (ErrorException $e) {
            throw new ErrorException;
        }
//        if ($odds != '' && $odds != null) {
            $win = WinOdds::firstOrCreate(['odds' => $odds, 'match_id' => $match->id, 'game_type_id' =>$game_type_id]);
            $win->save();
            return $odds;
//        }

    }
}