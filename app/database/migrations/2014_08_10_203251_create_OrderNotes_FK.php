<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderNotesFK extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('orderNotes', function(Blueprint $table)
		{
			$table->foreign('OrderID')->references('id')->on('orders');
                        $table->foreign('ItemID')->references('id')->on('items');
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
		Schema::table('orderNotes', function(Blueprint $table)
		{
			//
		});
	}

}
