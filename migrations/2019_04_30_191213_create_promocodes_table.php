<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromocodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('promocodes.table', 'promocodes'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 32)->unique();
            $table->enum('type', ['fixed', 'percent'])->default('fixed');
            $table->double('reward', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->integer('quantity')->default(-1);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create(config('promocodes.relation_table', 'promocode_user'), function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('promocode_id');
            
            $table->timestamps();

            $table->primary(['user_id', 'promocode_id']);

            $table->foreign('user_id')->references('id')->on(config('promocodes.users_table', 'users'));
            $table->foreign('promocode_id')->references('id')->on(config('promocodes.table', 'promocodes'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('promocodes.relation_table', 'promocode_user'));
        Schema::dropIfExists(config('promocodes.table', 'promocodes'));
    }
}
