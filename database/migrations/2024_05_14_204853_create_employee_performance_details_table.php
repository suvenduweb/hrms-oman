<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmployeePerformanceDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employee_performance_details', function(Blueprint $table)
		{
			$table->increments('employee_performance_details_id');
			$table->integer('employee_performance_id')->unsigned();
			$table->integer('performance_criteria_id')->unsigned();
			$table->integer('rating');
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
		Schema::drop('employee_performance_details');
	}

}
