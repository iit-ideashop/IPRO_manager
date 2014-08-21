<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGLFK extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ledgerEntries', function(Blueprint $table)
		{
			$table->foreign('AccountNumber')->references('id')->on('accounts');
                        $table->foreign('EnteredBy')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ledgerEntries', function(Blueprint $table)
		{
			//
		});
	}

}
