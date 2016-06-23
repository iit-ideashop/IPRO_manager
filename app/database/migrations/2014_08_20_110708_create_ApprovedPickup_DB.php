<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovedPickupDB extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('approvedPickups', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('PersonID')->unsigned();
                        $table->integer('OrderID')->unsigned();
                        $table->integer('ApproverID')->unsigned();
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
		Schema::drop('approvedPickups');
	}

}
