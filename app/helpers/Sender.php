<?php

class Sender
{
    public static function sendMail($matches)
    {
        $arr = array();
        $ms = array();
        $league = null;
        foreach ($matches as $match) {
            $league = League::find($match->league_id);
            $games = Game::where('match_id', $match->id)
                ->where('odds', '>', 0)
                ->get();
            $ms[$match->id] = $match;
            foreach ($games as $game) {
                $game_type = GameType::find($game->game_type_id)->name;
                $arr[$game->user_id][$game->match_id][$game_type] = array();
                $arr[$game->user_id][$game->match_id][$game_type]['bsf'] = $game->bsf;
                $arr[$game->user_id][$game->match_id][$game_type]['length'] = $game->current_length;
                $arr[$game->user_id][$game->match_id][$game_type]['bet'] = $game->bet;
                $arr[$game->user_id][$game->match_id][$game_type]['odds'] = $game->odds;
                $arr[$game->user_id][$game->match_id][$game_type]['income'] = $game->income;
                $arr[$game->user_id][$game->match_id][$game_type]['profit'] = $game->profit;
            }
        }

        if (count($arr) > 0) {
            foreach ($arr as $user_id => $games) {
                $user = User::find($user_id);
                $subject = '';
                Mail::send('emails.confirm', ['body' => $games, 'matches' => $ms, 'link_to_group' => URL::to("/").'/play', 'confirm_link' => URL::to("/").'/play/confirm/all/'.$league->country_alias], function ($message) use ($user, $subject) {
                    $message->to([$user->email => $user->name])
                        ->subject($subject);
                });
            }
        }

    }
} 