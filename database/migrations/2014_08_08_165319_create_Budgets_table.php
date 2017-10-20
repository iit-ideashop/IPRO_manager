<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('budgets', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('AccountID')->unsigned();
                        $table->double('Amount',9,2);
                        $table->text('Terms');
                        $table->integer('Requester')->unsigned();
                        $table->integer('Approver')->unsigned();
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
		Schema::drop('Budgets');
	}

}
