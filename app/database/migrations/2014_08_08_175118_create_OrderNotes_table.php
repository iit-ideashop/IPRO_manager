<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderNotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orderNotes', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('OrderID')->unsigned();
                        $table->integer('ItemID')->nullable()->unsigned();
                        $table->text('Notes');
                        $table->integer('EnteredBy')->unsigned();
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
		Schema::drop('OrderNotes');
	}

}
