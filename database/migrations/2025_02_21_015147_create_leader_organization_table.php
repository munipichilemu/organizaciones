<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leader_organization', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('leader_id');
            $table->foreignUlid('organization_id');
            $table->foreignIdFor(\App\Models\MemberPosition::class)->default('member');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leader_organization');
    }
};
