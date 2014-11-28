<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('matches', function(Blueprint $table)
		{
			$table->string('id')->unique();
            $table->timestamp('date_time');
            $table->string('home_team_id');
            $table->string('away_team_id');
            $table->integer('home_goals');
            $table->integer('away_goals');
            $table->char('short_result');
            $table->integer('league_id');
            $table->string('state');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('matches');
	}

}
