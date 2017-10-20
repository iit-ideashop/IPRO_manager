<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetFK extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('budgets', function(Blueprint $table)
		{
			$table->foreign('AccountID')->references('id')->on('accounts');
                        $table->foreign('Requester')->references('id')->on('users');
                        $table->foreign('Approver')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('Budget', function(Blueprint $table)
		{
			//
		});
	}

}
