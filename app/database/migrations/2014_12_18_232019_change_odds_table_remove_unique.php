<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeOddsTableRemoveUnique extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('win_odds', function(Blueprint $table)
        {
//            $table->dropColumn('id');
            $table->dropColumn('match_id');
//            $table->string('match_id');
//            $table->integer('game_type_id');
//            $table->unique( array('game_type_id','match_id') );
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
