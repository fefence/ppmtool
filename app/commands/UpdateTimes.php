<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class UpdateTimes extends Command {

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
        foreach($leagues as $league) {
            $res = Parser::parseNextMatches($league->id);
            foreach($res as $m => $times) {
                if ($times['old'] != $times['new']) {
                    $r[$league->country_alias][$m] = $times['old'];
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
        return array(
//			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}
