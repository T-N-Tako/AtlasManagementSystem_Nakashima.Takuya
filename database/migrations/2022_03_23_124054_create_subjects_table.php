<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject_name', 60)->comment('科目名');
            $table->timestamps(); // ← これがないと created_at と updated_at が追加されない
            // $table->timestamp('created_at')->nullable()->comment('登録日時');
            // $table->timestamp('updated_at')->nullable()->comment('更新日時'); // 追加するなら必要
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subjects');
    }
}
