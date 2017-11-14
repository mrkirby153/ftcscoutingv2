<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuestionsAddOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_questions', function (Blueprint $table) {
            $table->integer('order')->after('extra_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_questions', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
