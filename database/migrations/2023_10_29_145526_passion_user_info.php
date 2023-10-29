<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Passion;
use App\Models\UserInfo;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('passion_user_info', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Passion::class);
            $table->foreignIdFor(UserInfo::class);
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
