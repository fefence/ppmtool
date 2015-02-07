<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class UpdateTimes extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ppm:updatetimes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update matches time and dates';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $r = array();
        $leagues = League::all();
        foreach ($leagues as $league) {
            $next_before = Updater::getNextMatchesForLeague($league->id);
            $res = Parser::parseNextMatches($league->id);
            $next_after = Updater::getNextMatchesForLeague($league->id);
            foreach ($res as $m => $times) {
                if ($times['old'] != $times['new']) {
                    $r[$league->country_alias][$m] = $times['old'];

                }
            }
            $users = User::all();
            $game_types = GameType::all();
            if ($next_after != $next_before) {
                foreach ($next_before as $b) {
                    foreach ($users as $user) {
                        foreach ($game_types as $game_type) {
                            $series = Series::where('end_match_id', $b->id)
                                ->where('game_type_id', $game_type)
                                ->get();
                            $games = Game::where('match_id', $b->id)
                                ->where('user_id', $user->id)
                                ->where('game_type_id', $game_type->id)
                                ->get();
                            $bsf = 0;
//                            return $bsf;
                            if ($games != null && count($games) > 0) {
                                foreach ($games as $game) {
                                    $bsf = $bsf + $game->bsf;
                                    $game->delete();
                                }
                                foreach ($next_after as $next) {
                                    $game = Game::firstOrCreate(['user_id' => $user->id, 'match_id' => $next->id, 'game_type_id' => $game_type->id, 'confirmed' => 0, 'current_length' => $series->length, 'series_id' => $series->id]);
                                    $game->bsf = $bsf / count($next_after);
                                    $odds = Parser::getOdds($next->id)[$game_type->id];
                                    if ($odds != null && $odds != -1) {
                                        $game->odds = $odds;
                                    }
                                    $game->save();
                                }
                            } else {
                                echo "user: ".$user->id." game_type ".$game_type->id."<br>\n";
                            }
                        }

                    }
                }

            }
        }


        if (count($r)) {
            Sender::sendMailResceduledMatches($r);
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array( //			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}
