<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIproTracksTbl extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('iprotracks', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('trackID')->unsigned();
                        $table->string('iproNumber');
                        $table->string('iproName');
                        $table->text('link');
			$table->timestamps();
                        $table->foreign('trackID')->references('id')->on('tracks');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('iprotracks');
	}

}
