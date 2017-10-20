<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetsRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('budgetRequests', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('AccountID')->unsigned();
                        $table->double('Amount',9,2);
                        $table->text('Request');
                        $table->integer('Status')->unsigned();
                        $table->integer('Requester')->unsigned();
                        $table->integer('ModifiedBy')->unsigned()->nullable();
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
		Schema::drop('BudgetRequests');
	}

}
