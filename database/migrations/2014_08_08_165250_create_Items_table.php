<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('items', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('OrderID')->unsigned();
                        $table->string('Name');
                        $table->text('Link');
                        $table->string('PartNumber');
                        $table->double('Cost',9,2);
                        $table->integer('Quantity');
                        $table->double('TotalCost',9,2);
                        $table->text('Justification');
                        $table->boolean('Returning')->default(false);
                        $table->integer('Status')->unsigned();
                        $table->integer('ModifiedBy')->unsigned();
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
		Schema::drop('Items');
	}

}
