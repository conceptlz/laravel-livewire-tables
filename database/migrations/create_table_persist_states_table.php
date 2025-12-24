<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('table_persist_states', function (Blueprint $table) {
            $table->id();
            $table->string('table_name')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('session_id')->nullable()->index();
            $table->json('state_data');
            $table->timestamp('expires_at')->index();
            $table->timestamps();

            $table->unique(['table_name', 'user_id', 'session_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('table_persist_states');
    }
};
