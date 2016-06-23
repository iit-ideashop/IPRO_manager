<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartNumberNullable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
			DB::unprepared("ALTER TABLE `items` CHANGE `PartNumber` `PartNumber` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('items', function(Blueprint $table)
		{
			//
		});
	}

}
