<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');

            $table->morphs('documentable');

            $table->unsignedBigInteger('file_id')->nullable();
            $table->foreign('file_id')->references('id')->on('files')
                ->onUpdate('restrict')->onDelete('restrict');

            $table->longText('text')->nullable();

            $table->timestamps();
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `documents` ADD FULLTEXT(`text`)');
        }
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};
