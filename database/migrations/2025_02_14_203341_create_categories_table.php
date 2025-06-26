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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('categories')->insert([
            [
                'name' => 'Animalista',
                'description' => 'Protección de fauna, lucha contra el maltrato animal y promoción de derechos de los animales',
                'icon' => 'fas-paw',
            ],
            [
                'name' => 'Cultural',
                'description' => 'Promoción artística, preservación patrimonial y actividades culturales generales',
                'icon' => 'fas-theater-masks',
            ],
            [
                'name' => 'Discapacidad',
                'description' => 'Inclusión social, accesibilidad y defensa de derechos para personas con discapacidad',
                'icon' => 'fas-wheelchair',
            ],
            [
                'name' => 'Mujeres',
                'description' => 'Empoderamiento femenino, equidad de género y prevención de violencia contra la mujer',
                'icon' => 'fas-person-dress',
            ],
            [
                'name' => 'Salud',
                'description' => 'Prevención de enfermedades, atención médica comunitaria y promoción de hábitos saludables',
                'icon' => 'fas-briefcase-medical',
            ],
            [
                'name' => 'Seguridad',
                'description' => 'Prevención del delito, capacitación en seguridad ciudadana y redes comunitarias de protección',
                'icon' => 'fas-shield-alt',
            ],
            [
                'name' => 'Vivienda',
                'description' => 'Mejoramiento habitacional, acceso a viviendas sociales y gestión de comités de vivienda',
                'icon' => 'fas-house',
            ],
            [
                'name' => 'Ambiental',
                'description' => 'Conservación de ecosistemas, educación ecológica y acciones contra el cambio climático',
                'icon' => 'fas-tree',
            ],
            [
                'name' => 'Étnica',
                'description' => 'Defensa de pueblos originarios, migrantes y minorías raciales o culturales',
                'icon' => 'fas-earth-americas',
            ],
            [
                'name' => 'Folclórica',
                'description' => 'Rescate de tradiciones locales, danzas típicas y expresiones culturales ancestrales',
                'icon' => 'fas-guitar',
            ],
            [
                'name' => 'Infancia y juventud',
                'description' => 'Protección integral, educación no formal y desarrollo de niños, niñas y adolescentes',
                'icon' => 'fas-child-reaching',
            ],
            [
                'name' => 'Social',
                'description' => 'Asistencia comunitaria, beneficencia y clubes de servicio (Leones, Rotary, Damas)',
                'icon' => 'fas-people-group',
            ],
            [
                'name' => 'Adultos Mayores',
                'description' => 'Recreación, defensa de derechos y programas para la tercera edad',
                'icon' => 'fas-person-cane',
            ],
            [
                'name' => 'Padres, madres y apoderados',
                'description' => 'Asociaciones de padres y madres para apoyo educativo o comunitario',
                'icon' => 'fas-people-line',
            ],
            [
                'name' => 'Deportes',
                'description' => 'Clubes deportivos, gestión de instalaciones y promoción de actividad física',
                'icon' => 'fas-basketball',
            ],
            [
                'name' => 'Bomberos',
                'description' => 'Cuerpos de bomberos voluntarios y organizaciones de apoyo a su labor',
                'icon' => 'fas-fire',
            ],
            [
                'name' => 'Educación',
                'description' => 'Mejora educativa, apoyo pedagógico y proyectos escolares/comunitarios',
                'icon' => 'fas-book-reader',
            ],
            [
                'name' => 'Vecinal',
                'description' => 'Gestión territorial: juntas de vecinos, uniones comunales y comités barriales',
                'icon' => 'fas-people-roof',
            ],
            [
                'name' => 'Mutualidad',
                'description' => 'Ayuda mutua para beneficios socioeconómicos (corporaciones y sociedades mutualistas)',
                'icon' => 'fas-handshake-angle',
            ],
            [
                'name' => 'ONGs',
                'description' => 'Acción social, desarrollo sostenible y proyectos especializados no gubernamentales',
                'icon' => 'fas-hand-holding-hand',
            ],
            [
                'name' => 'Religiosa',
                'description' => 'Comunidades de fe, actividades espirituales y promoción de valores religiosos',
                'icon' => 'fas-person-praying',
            ],
            [
                'name' => 'Bienestar',
                'description' => 'Asistencia social especializada: alimentación, vestuario y apoyo a grupos vulnerables',
                'icon' => 'fas-hand-holding-heart',
            ],
            [
                'name' => 'Política',
                'description' => 'Promoción de ideologías, formación cívica y actividades políticas no partidistas',
                'icon' => 'fas-check-to-slot',
            ],
            [
                'name' => 'Otras',
                'description' => 'Casos no clasificables en las categorías anteriores',
                'icon' => 'far-circle-dot',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
