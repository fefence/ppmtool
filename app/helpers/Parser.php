<?php

class Parser
{

    private static $current_season = '2014-2015';

    /*
     * betexplorer parsing
     */

    public static function parseNextMatches($league_id)
    {

        $res = array();
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
            if ($cols->length > 1) {
                $a = $cols->item(1)->getElementsByTagName('a');
                foreach ($a as $link) {
                    $href = $link->getAttribute("href");
                    $arr = explode("/", $href);
                    if (count($arr) > 2) {
                        $id = $arr[count($arr) - 2];
                        $match = Match::find($id);
                        if ($match != null) {
                            $res[$id] = array();
                            $res[$id]['old'] = $match->date_time;
                        } else {
                            $match = Match::firstOrCreate(['id' => $id]);
                        }
                        $dt = $cols->item(8)->nodeValue;
                        $dtarr = explode(' ', $dt);
                        $date = $dtarr[0];
                        $ha = $cols->item(1)->nodeValue;
                        $tarr = explode(' - ', $ha);
                        $match->home = $tarr[0];
                        $match->away = $tarr[1];
                        $match->short_result = '-';
                        $match->league_id = $league_id;
                        $datearr = explode('.', $date);
                        $time = date('H:i:s', strtotime($dtarr[1]));
                        $timestamp = strtotime($datearr[2] . '-' . $datearr[1] . '-' . $datearr[0] . " " . $time) + 60 * 60;
                        $time = date('H:i:s', $timestamp);
                        $match->date_time = $datearr[2] . '-' . $datearr[1] . '-' . $datearr[0] . " " . $time;
                        $match->season = self::$current_season;
                        $match->save();
                        if (array_key_exists($id, $res)) {
                            $res[$id]['new'] = $match->date_time;
                        }
                    }
                }

            }
        }
        return $res;
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
                $reds = Parser::getRedCards($match->id);
                $match->home_red = $reds[0];
                $match->away_red = $reds[1];
                if ($finished == 'Awarded') {
                    $match->state = 'Awarded';
                }
            }
//            $match->save();
        }
        return $matches;
    }

    public static function getOdds($match_id)
    {
        $match = Match::find($match_id);

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
            $odds00 = $odds_arr['d']['oddsdata']["back"]['E-8-2-0-0-1']['odds'][16][0];
            $odds11 = $odds_arr['d']['oddsdata']["back"]['E-8-2-0-0-3']['odds'][16][0];
            $odds22 = $odds_arr['d']['oddsdata']["back"]['E-8-2-0-0-7']['odds'][16][0];
            $odds01 = $odds_arr['d']['oddsdata']["back"]['E-8-2-0-0-4']['odds'][16][0];
            $odds02 = $odds_arr['d']['oddsdata']["back"]['E-8-2-0-0-9']['odds'][16][0];
            $odds10 = $odds_arr['d']['oddsdata']["back"]['E-8-2-0-0-2']['odds'][16][0];
            $odds20 = $odds_arr['d']['oddsdata']["back"]['E-8-2-0-0-5']['odds'][16][0];
            $odds12 = $odds_arr['d']['oddsdata']["back"]['E-8-2-0-0-8']['odds'][16][0];
            $odds21 = $odds_arr['d']['oddsdata']["back"]['E-8-2-0-0-6']['odds'][16][0];
        } catch (ErrorException $e) {
            $odds00 = -1;
            $odds11 = -1;
            $odds22 = -1;
            $odds10 = -1;
            $odds12 = -1;
            $odds20 = -1;
            $odds21 = -1;
            $odds01 = -1;
            $odds02 = -1;
        }
        $odds1x2 = '-1';
        $url = "http://www.betexplorer.com/gres/ajax-matchodds.php?t=n&e=".$match->id."&b=1x2";
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
                            $odds1x2 = $oddsX;
                            break 1;
                        }
                    }
                }
            }
        }

        return array(1 => $odds1x2, 2 => $odds00, 3 => $odds11, 4 => $odds22, 5 => $odds01, 6 => $odds02, 7 => $odds10, 8 => $odds20, 9 => $odds12, 10 => $odds21);

    }


    private static function get_http_response_code($url)
    {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }


    public static function getRedCards($match_id) {
        $url = "http://d.livescore.in/x/feed/d_su_" . $match_id . "_en_4";
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_URL, $url);
        $header = array(
            'Accept-Encoding:gzip,deflate,sdch',
            "X-Fsign: SW9D1eZo",
            'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.142 Safari/535.19',
        );
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.142 Safari/535.19');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_REFERER, 'http://kat.ph');
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate,sdch');
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);

        $html = curl_exec($curl);
        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = FALSE;
        @$dom->loadHTML($html);

        $home_red = 0;
        $away_red = 0;
        $table = $dom->getElementById("parts");
        $rows = $table->getElementsByTagName('tr');
        foreach($rows as $row) {
            $cols = $row->getElementsByTagName('td');
            if ($cols->length > 0) {
                $home = $cols->item(0);
                $divs = $home->getElementsByTagName('div');
                foreach($divs as $div){
                    $attr = $div->getAttribute('class');
                    if ($attr == 'icon-box r-card') {
                        $home_red ++;
                    }
                }
                if ($cols->length == 2) {
                    $away = $cols->item(1);
                    $divs = $away->getElementsByTagName('div');
                    foreach($divs as $div){
                        $attr = $div->getAttribute('class');
                        if ($attr == 'icon-box r-card') {
                            $away_red ++;
                        }
                    }
                } else if ($cols->length == 3) {
                    $away = $cols->item(2);
                    $divs = $away->getElementsByTagName('div');
                    foreach($divs as $div){
                        $attr = $div->getAttribute('class');
                        if ($attr == 'icon-box r-card') {
                            $away_red ++;

                        }
                    }
                }
            }
//            $home_red = $home_red + count($home->getElementsByClassName('icon r-card'));
//            $away_red = $away_red + count($away->getElementsByClassName('icon r-card'));
//            foreach()


        }
//        return $html;
        $h = '';
        for($i = 0; $i < $home_red; $i ++) {
            $h = $h."<img src='/images/red.jpg'>&nbsp;";
        }
        $a = '';
        for($i = 0; $i < $away_red; $i ++) {
            $a = $a."<img src='/images/red.jpg'>&nbsp;";
        }
        return [$home_red, $away_red];
    }

    public static function parseMatchesForLeagueAndSeason($league_details_id, $season) {
        //www.betexplorer.com/soccer/india/i-league-2003-2004/results/
        //leagueresults_tbody
        $league = League::find($league_details_id);
        $url = "http://www.betexplorer.com/soccer/".$league->country."/".$league->name."-".$season."/results/";
        if (Parser::get_http_response_code($url) != "200") {
            $url = "http://www.betexplorer.com/soccer/".$league->country."/".$league->name."/results/";
            if (Parser::get_http_response_code($url) != '200') {
                $url = "http://www.betexplorer.com/soccer/".$league->country."/".$league->name."-".$season."/results/";

//                return "Wrong fixtures url! --> $url";
            }
        }
//        return $url;
        $data = file_get_contents($url);

        $dom = new domDocument;

        @$dom->loadHTML($data);
        $dom->preserveWhiteSpace = false;

        $table = $dom->getElementById("leagueresults_tbody");
        $rows = $table->getElementsByTagName("tr");
        foreach($rows as $row) {
            $cols = $row->getElementsByTagName("td");
            if ($cols->length > 0) {
                $attrs = $cols->item(0)->getElementsByTagName("a");
                $urlarr = explode("=", $attrs->item(0)->getAttribute('href'));
                $id = $urlarr[1];
                $ha = explode(' - ', $cols->item(0)->nodeValue);
                $match = Match::firstOrCreate(['id' => $id]);
                $match->home = $ha[0];
                $match->away = $ha[1];
                $ra = explode(':', $cols->item(1)->nodeValue);
                if (count($ra) > 1) {
                    $match->home_goals = $ra[0];
                    $match->away_goals = $ra[1];
                    if ($ra[0] > $ra[1]) {
                        $match->short_result = 'H';
                    } else if ($ra[0] < $ra[1]) {
                        $match->short_result = 'A';
                    } else {
                        $match->short_result = 'D';
                    }
                } else {
                    echo $match->id."<br>";
//                    return $match;
//                    $match->resultShort = '';
                }
                $match->league_id = $league_details_id;
                $match->season = $season;
                $match->save();
                echo $match->id;
//                Parser::parseTimeDate($match);
            }

        }
        return "finished";
    }

    public static function getMatchMinute($id) {
        //event_stages:
        // 12 -> first half
        // 38 -> HT
        // 13 -> second half
        $url = "http://www.betexplorer.com/gres/live-results.php";
        $json_data = file_get_contents($url);
//        $arr = array();
        $arr = json_decode($json_data, true);
        $st = '';
        $min = $arr['events'][$id]['minute'];
        $stage = $arr['events'][$id]["event_stage_id"];
        switch ($stage) {
            case 12 :
                $st = '1H';
                break;
            case 38:
                $st = 'HT';
                break;
            case 13:
                $st = '2H';
                break;
        }
        return [$st, $min];
    }
}