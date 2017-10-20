<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('PeopleID')->unsigned();
                        $table->integer('ClassID')->unsigned();
                        $table->string('Phone')->nullable();
                        $table->double('OrderTotal',9,2);
                        $table->string('Description');
                        $table->string('NotificationPreference')->default(0);
                        $table->integer('Status')->unsigned();
                        $table->integer('ModifiedBy')->unsigned();
			$table->timestamps();
                        $table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Orders');
	}

}
