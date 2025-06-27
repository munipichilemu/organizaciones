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
        Schema::create('member_positions', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('title');
            $table->integer('order')->default('-1');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('member_positions')->insert([
            ['slug' => 'president', 'title' => 'Presidente', 'order' => '10'],
            ['slug' => 'superintendent', 'title' => 'Superintendente', 'order' => '11'],

            ['slug' => 'vicepresident', 'title' => 'Vicepresidente', 'order' => '20'],
            ['slug' => 'first_commander', 'title' => '1º Comandante', 'order' => '21'],

            ['slug' => 'secretary', 'title' => 'Secretario', 'order' => '30'],

            ['slug' => 'treasurer', 'title' => 'Tesorero', 'order' => '40'],
            ['slug' => 'assistant_treasurer', 'title' => 'Protesorero', 'order' => '41'],

            ['slug' => 'director', 'title' => 'Director', 'order' => '50'],
            ['slug' => 'delegate', 'title' => 'Delegado', 'order' => '51'],

            ['slug' => 'member', 'title' => 'Miembro', 'order' => '90'],
            ['slug' => 'other', 'title' => 'Otro cargo o posición', 'order' => '99'],
        ]);
    }
};
