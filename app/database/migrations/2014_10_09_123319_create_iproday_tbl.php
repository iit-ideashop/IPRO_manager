<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIprodayTbl extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('iproday', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->dateTime('eventDate');
                        $table->dateTime('judgesStart');
                        $table->dateTime('judgesEnd');
                        $table->dateTime('guestsStart');
                        $table->dateTime('guestsEnd');
                        $table->text('location');
                        $table->text('locationLink');
                        $table->dateTime('registrationStart');
                        $table->dateTime('registrationEnd');
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
		Schema::drop('iproday');
	}

}
