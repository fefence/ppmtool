<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeOddsTableAddUnique extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//$table->unique( array('email','name') );
        Schema::table('win_odds', function(Blueprint $table)
        {
//            $table->dropColumn('id');
//            $table->dropColumn('match_id');
            $table->integer('game_type_id');
            $table->unique( array('game_type_id','match_id') );
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
