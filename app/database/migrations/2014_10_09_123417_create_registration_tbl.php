<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrationTbl extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('registration', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('iproday')->unsigned();
                        $table->integer('registrant')->unsigned();
                        $table->enum('tyoe',array('Judge','Guest'));
                        $table->boolean('judgedBefore');
                        $table->text('trackPreferences');
                        $table->text('dietaryRestrictions');
			$table->timestamps();
                        $table->foreign('iproday')->references('id')->on('iproday');
                        $table->foreign('registrant')->references('id')->on('registrants');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('registration');
	}

}
