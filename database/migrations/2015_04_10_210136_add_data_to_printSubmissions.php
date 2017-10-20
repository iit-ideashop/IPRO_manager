<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataToPrintSubmissions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('printSubmissions', function(Blueprint $table)
		{
			$table->integer('barcode')->unsigned()->nullable()->default(NULL)->after('status');
			$table->integer("pickup_UserID")->unsigned()->nullable()->default(NULL)->after("UserID");
			$table->foreign("pickup_UserID")->references("id")->on("users");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('printSubmissions', function(Blueprint $table)
		{
			//
		});
	}

}
