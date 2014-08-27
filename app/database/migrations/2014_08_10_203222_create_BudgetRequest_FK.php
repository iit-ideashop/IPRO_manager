<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetRequestFK extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('budgetRequests', function(Blueprint $table)
		{
			$table->foreign('AccountID')->references('id')->on('accounts');
                        $table->foreign('Status')->references('id')->on('budgetStatus');
                        $table->foreign('Requester')->references('id')->on('users');
                        $table->foreign('ModifiedBy')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('budgetRequest', function(Blueprint $table)
		{
			//
		});
	}

}
