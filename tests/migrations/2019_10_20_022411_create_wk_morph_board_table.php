<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateWkMorphBoardTable extends Migration
{
    public function up()
    {
        Schema::create(config('wk-core.table.morph-board.boards'), function (Blueprint $table) {
            $table->uuid('id');
            $table->nullableUuidMorphs('host');
            $table->string('type', 20);
            $table->uuid('user_id');
            $table->string('serial')->nullable();
            $table->string('identifier')->nullable();
            $table->boolean('is_highlighted')->default(0);
            $table->boolean('is_enabled')->default(0);

            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')
                  ->on(config('wk-core.table.user'))
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->primary('id');
            $table->index(['host_type', 'host_id', 'type']);
            $table->index(['host_type', 'host_id', 'type', 'is_highlighted']);
            $table->index(['host_type', 'host_id', 'type', 'is_enabled']);
            $table->index('type');
            $table->index('serial');
            $table->index('identifier');
            $table->index('is_highlighted');
            $table->index('is_enabled');
        });
        if (!config('wk-morph-board.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.morph-board.boards_lang'), function (Blueprint $table) {
                $table->uuid('id');
                $table->uuidMorphs('morph');
                $table->uuid('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->longText('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');

                $table->primary('id');
            });
        }
    }

    public function down() {
        Schema::dropIfExists(config('wk-core.table.morph-board.boards_lang'));
        Schema::dropIfExists(config('wk-core.table.morph-board.boards'));
    }
}
