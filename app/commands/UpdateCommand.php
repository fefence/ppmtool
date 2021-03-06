<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ppm:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update matches time and date';

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
        $value = $this->argument('league_id');
        Updater::update($value);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('league_id', InputArgument::REQUIRED, 'League Id'),
        );
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
