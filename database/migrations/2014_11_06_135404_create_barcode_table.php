<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarcodeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('barcodes', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();//The barcode
                        $table->enum("type",array('ITEM'));
                        $table->integer('reference')->unsigned();
                        $table->integer('createdBy')->unsigned();
			$table->timestamps();
                        $table->foreign('createdBy')->references('id')->on('users');
                });

        }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('barcodes');
	}

}
