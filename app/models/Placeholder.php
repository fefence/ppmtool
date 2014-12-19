<?php


class Placeholder extends Eloquent
{
    protected $table = 'placeholders';
    public static $unguarded = true;
    public $timestamps = false;

    public function match()
    {
        return $this->belongsTo('Match');
    }

    public function game_type()
    {
        return $this->belongsTo('GameType');
    }

    public static function createPlaceholders($matches, $user_id, $game_type_id)
    {
        $next_matches = Updater::getNextMatches($matches);
        foreach ($next_matches as $next) {
            $pl = Placeholder::firstOrNew(['match_id' => $next->id, 'user_id' => $user_id, 'game_type_id' => $game_type_id]);
            $pl->league_id = $next->league_id;
            $pl->active = 1;
            $pl->save();
        }
    }

    public static function deactivate($league_id)
    {
        $placeholders = Placeholder::where('league_id', $league_id)->get();
        foreach ($placeholders as $pl) {
            $pl->active = 0;
            $pl->save();
        }
    }
}