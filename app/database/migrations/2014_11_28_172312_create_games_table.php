<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('games', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('user_id');
            $table->integer('game_type_id');
            $table->string('match_id');
            $table->decimal('bet');
            $table->decimal('odds');
            $table->decimal('bsf');
            $table->decimal('income');
            $table->decimal('profit');
            $table->integer('current_length');
            $table->integer('series_id');
            $table->boolean('confirmed');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('games');
	}

}
