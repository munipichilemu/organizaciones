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
        Schema::create('organizations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->integer('registration_id')->index();
            $table->string('name');
            $table->rutNullable('rut')->index();
            $table->string('information_source');
            $table->string('address')->nullable();
            $table->foreignIdFor(\App\Models\OrganizationType::class);
            $table->foreignIdFor(\App\Models\Category::class);
            $table->foreignIdFor(\App\Models\OrganizationState::class)->nullable();
            $table->date('registered_at')->nullable();
            $table->date('confirmed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
