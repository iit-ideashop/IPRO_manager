<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoPreferenceToRegistration extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('registration', function(Blueprint $table)
		{
			$table->boolean('noPreferenceTrack')->after('trackPreferences');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('registration', function(Blueprint $table)
		{
			//
		});
	}

}
