<?php

class LivescoreController extends \BaseController
{
    public function livescore($fromdate = '', $todate = '')
    {

        list($fromdate, $todate) = Utils::calcDates($fromdate, $todate);


        $ms = Match::where('date_time', '>=', $fromdate)
            ->join('leagues', 'leagues.id', '=', 'matches.league_id')
            ->where('hidden', 0)
            ->where('date_time', '<=', $todate)
            ->orderBy('date_time')
            ->select(DB::raw('matches.id'))
            ->lists('matches.id');

        $res = array();
        foreach ($ms as $match_id) {
            $match = Match::find($match_id);
            $res[$match->id] = array();
            $res[$match->id]['match'] = $match;
            $res[$match->id]['league'] = League::find($match->league_id);
            $res[$match->id]['settings'] = Game::where('user_id', Auth::user()->id)
                ->where('match_id', $match->id)
                ->with('game_type')
                ->orderBy('game_type_id')
                ->select(DB::raw("distinct game_type_id"))
                ->get();
        }
        if (count($res) == 0) {
            $no_info = true;
        } else {
            $no_info = false;
        }
        return View::make('livescore')->with(['matches' => $res, 'fromdate' => $fromdate, 'todate' => $todate, 'base' => 'list', 'no_info' => $no_info]);
    }

    public function livescorebycountry($fromdate = '', $todate = '')
    {

        list($fromdate, $todate) = Utils::calcDates($fromdate, $todate);

        $res = array();

        $leagues = Match::where('date_time', '>=', $fromdate)
            ->join('leagues', 'leagues.id', '=', 'matches.league_id')
            ->where('date_time', '<=', $todate)
            ->orderBy('country')
            ->lists('league_id');
        $settings = array();
        foreach ($leagues as $league_id) {
            $league = League::find($league_id);
            $res[$league->country_alias] = Match::where('date_time', '>=', $fromdate)
                ->where('date_time', '<=', $todate)
                ->where('league_id', $league_id)
                ->orderBy('date_time')
                ->get();
            foreach ($res[$league->country_alias] as $m) {
                $sets = Game::where('user_id', Auth::user()->id)
                    ->where('match_id', $m->id)
                    ->with('game_type')
                    ->orderBy('game_type_id')
                    ->select(DB::raw("distinct game_type_id"))
                    ->get();
                $settings[$m->id] = $sets;
            }
        }
        if (count($res) == 0) {
            $no_info = true;
        } else {
            $no_info = false;
        }
//        return $settings;
//        $settings = Setting::where('user_id', Auth::user()->id);
//            ->where()
//        return $res;
        return View::make('livescorebycountry')->with(['matches' => $res, 'fromdate' => $fromdate, 'todate' => $todate, 'base' => 'listbycountry', 'settings' => $settings, 'no_info' => $no_info]);
    }

    public static function matchScore($match_id)
    {

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

//        return $html;
//        return View::make('matchfeed')->with(['html' => Parser::parseLivescoreForMatch($dom)]);
        return $html;
    }

    public static function getMatchCurrentRes($id)
    {
        $html = LivescoreController::matchScore($id);
        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = FALSE;
        @$dom->loadHTML($html);
        $finder = new DomXPath($dom);
        $classname = "p1_home";
        $h1 = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
        $home = '';
        $away = '';
        if ($h1->length > 0) {
            $home = $h1->item(0)->nodeValue;
        }
        $classname = "p2_home";
        $h2 = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
        if ($h2->length > 0) {
            $home = $home + $h2->item(0)->nodeValue;
        }
        $classname = "p1_away";
        $a1 = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
        if ($a1->length > 0) {
            $away = $a1->item(0)->nodeValue;
        }
        $classname = "p2_away";
        $a2 = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
        if ($a2->length > 0) {
            $away = $away + $a2->item(0)->nodeValue;
        }
        $reds = Parser::getRedCards($id);
        return [$home, $away, $reds[0], $reds[1]];
    }

    public static function test() {
        return View::make('test');
    }

}