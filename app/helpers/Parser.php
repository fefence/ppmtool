<?php

class Parser
{

    private static $current_season = '2014-2015';

    /*
     * betexplorer parsing
     */

    public static function parseNextMatches($league_id)
    {

        $baseUrl = "http://www.betexplorer.com/soccer/";

        $league = League::find($league_id);
        $url = $baseUrl . $league->country . "/" . $league->name . "/";
        if (Parser::get_http_response_code($url) != "200") {
            return "Wrong fixtures url! --> $url";
        }
        $data = file_get_contents($url);

        $dom = new domDocument;

        @$dom->loadHTML($data);
        $dom->preserveWhiteSpace = false;

        $table = $dom->getElementById("league-summary-next");
        $rows = $table->getElementsByTagName("tr");

        foreach ($rows as $row) {
            $cols = $row->getElementsByTagName('td');
            if ($cols->length > 0) {
                $a = $cols->item(1)->getElementsByTagName('a');
                foreach ($a as $link) {
                    $href = $link->getAttribute("href");
                    $arr = explode("/", $href);
                    if (count($arr) > 2) {
                        $id = $arr[count($arr) - 2];
                        $match = Match::firstOrCreate(['id' => $id]);

                        $dt = $cols->item(8)->nodeValue;
                        $dtarr = explode(' ', $dt);
                        $date = $dtarr[0];
                        $time = $dtarr[1];
                        $ha = $cols->item(1)->nodeValue;
                        $tarr = explode(' - ', $ha);

                        $match->home = $tarr[0];
                        $match->away = $tarr[1];
                        $match->short_result = '-';
                        $match->league_id = $league_id;
                        $datearr = explode('.', $date);
                        $match->date_time = $datearr[2] . '-' . $datearr[1] . '-' . $datearr[0] . " " . $time;
                        $match->season = self::$current_season;
                        $match->save();
//                        echo $match->id."<br>";
                    }
                }

            }
        }
    }

    public static function updateMatchesResult($matches)
    {
        foreach ($matches as $match) {
            $url = "http://www.livescore.in/match/" . $match->id . "/#match-summary";
            if (self::get_http_response_code($url) != "200") {
                return "Wrong match details url! --> $url";

            }
            $data = file_get_contents($url);

            $dom = new domDocument;

            @$dom->loadHTML($data);
            $dom->preserveWhiteSpace = false;

            $table = $dom->getElementById("flashscore");
            $rows = $table->getElementsByTagName("tr");

            $finished = trim($rows->item(2)->getElementsByTagName('td')->item(0)->nodeValue);
            if ($finished == null || trim($finished) == '') {
                $finished = trim($rows->item(3)->getElementsByTagName('td')->item(0)->nodeValue);
            }
            if ($finished == "Finished" || $finished == 'Awarded') {
                $res = explode('-', $rows->item(0)->getElementsByTagName('td')->item(2)->nodeValue);
                if (count($res) < 2) {
                    $res = explode('-', $rows->item(1)->getElementsByTagName('td')->item(1)->nodeValue);
                }
                if ($res[0] > $res[1]) {
                    $resultShort = 'H';
                } else if ($res[0] < $res[1]) {
                    $resultShort = 'A';
                } else {
                    $resultShort = 'D';
                }
                $match->home_goals = $res[0];
                $match->away_goals = $res[1];
                $match->short_result = $resultShort;
                if ($finished == 'Awarded') {
                    $match->state = 'Awarded';
                }
            }

        }
        return $matches;
    }

    private static function get_http_response_code($url)
    {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }
}