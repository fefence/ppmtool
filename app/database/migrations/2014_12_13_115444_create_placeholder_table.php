<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaceholderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('placeholders', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('game_id');
            $table->string('match_id');
            $table->decimal('bet');
            $table->decimal('odds');
            $table->decimal('bsf');
            $table->decimal('income');
            $table->decimal('profit');
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
		Schema::drop('placeholder');
	}

}
