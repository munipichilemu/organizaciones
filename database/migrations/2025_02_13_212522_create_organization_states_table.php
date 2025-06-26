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
        Schema::create('organization_states', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color');
            $table->string('icon');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('organization_states')->insert([
            ['name' => 'Vigente',       'color' => '{"key":"green","property":"--green-500","label":"Green","type":"rgb","value":"34, 197, 94"}',       'icon' => 'fas-check'],
            ['name' => 'Anulada',       'color' => '{"key":"red","property":"--red-500","label":"Red","type":"rgb","value":"239, 68, 68"}',             'icon' => 'fas-xmark'],
            ['name' => 'Disuelta',      'color' => '{"key":"zinc","property":"--zinc-500","label":"Zinc","type":"rgb","value":"113, 113, 122"}',        'icon' => 'fas-minus'],
            ['name' => 'No vigente',    'color' => '{"key":"amber","property":"--amber-500","label":"Amber","type":"rgb","value":"245, 158, 11"}',      'icon' => 'fas-exclamation-triangle'],
            ['name' => 'Extinta',       'color' => '{"key":"teal","property":"--teal-500","label":"Teal","type":"rgb","value":"20, 184, 166"}',         'icon' => 'fas-skull'],
            ['name' => 'Provisoria',    'color' => '{"key":"orange","property":"--orange-500","label":"Orange","type":"rgb","value":"249, 115, 22"}',   'icon' => 'fas-question'],
            ['name' => 'Vencida',       'color' => '{"key":"purple","property":"--purple-500","label":"Purple","type":"rgb","value":"168, 85, 247"}',   'icon' => 'fas-exclamation-circle'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_states');
    }
};
