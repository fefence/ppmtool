<?php

class ActionLogController extends \BaseController{
    public static function display() {
        $user_id = Auth::user()->id;
        $logs = ActionLog::where('user_id', $user_id)
            ->join('leagues', 'leagues.id', '=', 'action_log.league_id')
            ->orderBy('created_at', "desc")
            ->get();
        return View::make('actionlog')->with(['log' => $logs]);
    }
}