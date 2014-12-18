<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOddsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('win_odds', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('match_id');
            $table->decimal('odds');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('win_odds', function(Blueprint $table)
		{
			//
		});
	}

}
