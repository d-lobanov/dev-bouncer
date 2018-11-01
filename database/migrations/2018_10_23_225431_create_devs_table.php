<?php

use App\Dev;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Dev::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('owner_skype_id')->nullable();
            $table->string('owner_skype_username')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->text('comment')->nullable();
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
        Schema::dropIfExists(Dev::TABLE);
    }
}
