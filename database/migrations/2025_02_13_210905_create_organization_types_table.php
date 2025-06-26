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
        Schema::create('organization_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('organization_types')->insert([
            [
                'name' => 'Comunitaria/Vecinal',
                'description' => 'Organizaciones de base territorial reconocidas como forma de participación ciudadana por la Ley Nº19.418 sobre Juntas de Vecinos',
            ],
            [
                'name' => 'Corporación',
                'description' => 'Asociación sin fines de lucro constituida bajo las normas de personas jurídicas del Código Civil (arts. 545-564)',
            ],
            [
                'name' => 'Fundación',
                'description' => 'Organización destinada a bienes públicos, con patrimonio propio regulado por el Código Civil y Ley Nº20.500',
            ],
            [
                'name' => 'Organización Funcional',
                'description' => 'Agrupaciones por interés específico (cultural, profesional o sectorial) bajo Ley Nº20.500 de Participación Ciudadana',
            ],
            [
                'name' => 'Organización Territorial',
                'description' => 'Estructuras comunitarias oficiales basadas en división geográfica según Ley Nº19.418 y DFL Nº1/2006 del Ministerio del Interior',
            ],
            [
                'name' => 'Entidades Religiosas',
                'description' => 'Organizaciones de carácter religioso reconocidas por la Ley Nº19.638 sobre Constitución Jurídica de Iglesias',
            ],
            [
                'name' => 'Organizaciones Deportivas',
                'description' => 'Agrupaciones para práctica deportiva reguladas por la Ley Nº19.712 del Deporte y normativa IND',
            ],
            [
                'name' => 'Instituciones Educativas',
                'description' => 'Organizaciones con fines educativos bajo marco de la Ley General de Educación Nº20.370 y normativa MINEDUC',
            ],
            [
                'name' => 'Comunidades Indígenas',
                'description' => 'Organizaciones de pueblos originarios reconocidas por Ley Nº19.253 (CONADI) y Convenio 169 OIT',
            ],
            [
                'name' => 'Otros regímenes legales',
                'description' => 'Organizaciones no contempladas en las categorías anteriores, incluyendo: Bomberos (Ley Nº20.564), Cooperativas (Ley Nº19.832), Mutuales (Ley Nº16.744)',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_types');
    }
};
