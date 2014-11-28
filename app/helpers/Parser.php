<?php

class Parser
{

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
                        $match->resultShort = '-';
                        $match->league_details_id = $league_details_id;
                        $datearr = explode('.', $date);
                        $match->matchDate = $datearr[2] . '-' . $datearr[1] . '-' . $datearr[0];
                        $match->matchTime = $time;
//                        array_push($ids, $match);
                        $match->save();
                    }
                }


                // return $match;
            }
        }
    }
}