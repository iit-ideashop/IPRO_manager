<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultZeroBudgetToAccoutns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                DB::unprepared("ALTER TABLE `".$_ENV['database_db']."`.`accounts` CHANGE COLUMN `Balance` `Balance` DOUBLE(9,2) NOT NULL DEFAULT 0;");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('accounts', function(Blueprint $table)
		{
			//
		});
	}

}
