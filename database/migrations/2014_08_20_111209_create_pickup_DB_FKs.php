<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickupDBFKs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{   
                //$table->foreign('Semester')->references('id')->on('semesters');
                Schema::table('approvedPickups', function(Blueprint $table)
		{
			$table->foreign('PersonID')->references('id')->on('users');
                        $table->foreign('OrderID')->references('id')->on('orders');
                        $table->foreign('ApproverID')->references('id')->on('users');
		});
                
                Schema::table('pickups', function(Blueprint $table)
		{
			$table->foreign('PersonID')->references('id')->on('users');
                        $table->foreign('CreatedBy')->references('id')->on('users');
		});
                
		Schema::table('pickupItems', function(Blueprint $table)
		{
                        $table->foreign('ItemID')->references('id')->on('items');
                        $table->foreign('PickupID')->references('id')->on('pickups');
                });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pickups', function(Blueprint $table)
		{
			//
		});
	}

}
