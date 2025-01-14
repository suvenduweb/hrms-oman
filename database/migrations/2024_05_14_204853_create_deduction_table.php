<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeductionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('deduction', function(Blueprint $table)
		{
			$table->increments('deduction_id');
			$table->string('deduction_name', 250);
			$table->string('deduction_type', 100);
			$table->integer('percentage_of_basic');
			$table->integer('limit_per_month');
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
		Schema::drop('deduction');
	}

}
