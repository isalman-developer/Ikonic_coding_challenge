<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connection_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->comment('Sender User')->constrained('users', 'id')
            ->onDelete('cascade');
            $table->foreignId('receiver_id')->comment('Receiver User')->constrained('users', 'id')
            ->onDelete('cascade');
            $table->boolean('is_accepted')->default(false)
            ->comment('Status Column');
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
        Schema::dropIfExists('connection_requests');
    }
};
