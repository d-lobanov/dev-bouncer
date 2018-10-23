<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevsTable extends Migration
{
    const TABLE = 'devs';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('owner_skype_id')->nullable();
            $table->string('owner_skype_username')->nullable();
            $table->text('comment')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });

        DB::table(self::TABLE)->insert([
            ['name' => 'dev1'],
            ['name' => 'dev2'],
            ['name' => 'dev3'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE);
    }
}
