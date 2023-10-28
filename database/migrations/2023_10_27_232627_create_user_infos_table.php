<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('birthday');
            $table->integer('age')->default(0);
            $table->integer('height')->default(0);
            $table->string('gender');
            $table->string('show_gender');
            $table->string('relation');
            $table->string('education');
            $table->string('smoking');
            $table->string('drinking');
            $table->string('about_me');
            $table->string('first_date_idea');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_infos');
    }
};
