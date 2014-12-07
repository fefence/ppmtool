<?php

class LivescoreController extends \BaseController
{
    public function livescore($fromdate = '', $todate = '')
    {

        $fromdate = \Carbon\Carbon::now()->startOfDay();
        $todate = \Carbon\Carbon::now()->endOfDay()->addHours(10);


            $ms = Match::where('date_time', '>=', $fromdate)
                ->where('date_time', '<=', $todate)
                ->orderBy('date_time')
                ->lists('matches.id');

//        $matches = Match::getAllMatchesForDates($fromdate, $todate, $todate2, $all_ids);
        $res = array();
        foreach ($ms as $match_id) {
            $match = Match::find($match_id);
            $res[$match->id] = array();
            $res[$match->id]['match'] = $match;
            $res[$match->id]['league'] = League::find($match->league_id);
        }
//        return $res;
        return View::make('livescore')->with(['matches' => $res]);
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

    public static function getMatchCurrentRes($id) {
        $html = LivescoreController::matchScore($id);
        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = FALSE;
        @$dom->loadHTML($html);
        $finder = new DomXPath($dom);
        $classname="p1_home";
        $h1 = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
        $home = '';
        $away = '';
        if ($h1->length > 0) {
            $home = $h1->item(0)->nodeValue;
        }
        $classname="p2_home";
        $h2 = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
        if ($h2->length > 0) {
            $home = $home + $h2->item(0)->nodeValue;
        }
        $classname="p1_away";
        $a1 = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
        if ($a1->length > 0) {
            $away = $a1->item(0)->nodeValue;
        }
        $classname="p2_away";
        $a2 = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
        if ($a2->length > 0) {
            $away = $away + $a2->item(0)->nodeValue;
        }
        return $home." <span>:</span> ".$away;
    }


}