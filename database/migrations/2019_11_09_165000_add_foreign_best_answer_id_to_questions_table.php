<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignBestAnswerIdToQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->unsignedBigInteger('best_answer_id')->change();
            $table->foreign('best_answer_id')
                  ->references('id')
                  ->on('answers')
                  ->onDelete('SET NULL');
        });   // มันจะว่า ถ้าใช้ references id สามารถกำหนดได้ว่า ถ้า user_id ทุกลบ ให้ best_answer_id set เป็น NULL 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->unsignedInteger('best_answer_id')->change();
            $table->dropForeign(['best_answer_id']);
        });
    }
}
