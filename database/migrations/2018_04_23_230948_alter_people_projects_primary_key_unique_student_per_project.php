<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPeopleProjectsPrimaryKeyUniqueStudentPerProject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('PeopleProjects', function (Blueprint $table) {
            $table->unique(['UserId', 'ClassId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('PeopleProjects', function (Blueprint $table) {
            $table->dropIndex(['UserId', 'ClassId']);
        });
    }
}
