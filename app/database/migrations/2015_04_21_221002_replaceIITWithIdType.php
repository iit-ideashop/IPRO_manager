<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplaceIITWithIdType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('peoplesChoice', function(Blueprint $table)
		{
			$table->dropColumn("IIT");
			$table->string("IDType")->after("idnumber");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('peoplesChoice', function(Blueprint $table)
		{
			//
		});
	}

}
