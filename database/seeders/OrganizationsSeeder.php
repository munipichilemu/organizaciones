<?php

namespace Database\Seeders;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Laragear\Rut\Rut;
use Laragear\Rut\RutFormat;

class OrganizationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ruta al archivo CSV
        $csvFile = storage_path('app/imports/orgs.csv');

        // Leer el archivo CSV
        $csvData = File::get($csvFile);
        $rows = explode("\n", $csvData);
        $header = str_getcsv(array_shift($rows));

        // Preparar los datos para inserción
        $organizations = [];

        foreach ($rows as $row) {
            if (empty(trim($row))) {
                continue;
            }

            $data = str_getcsv($row);
            $rowData = array_combine($header, $data);

            // Formatear RUT usando laragear/rut
            /*$rut = null;
            if (! empty($rowData['rut_num']) && ! empty($rowData['rut_vd'])) {
                $rut = Rut::parse($rowData['rut_num'].$rowData['rut_vd'])->format(RutFormat::Strict);
            }*/

            // Determinar category_id (usar 24 si está vacío)
            $categoryId = empty($rowData['category_id']) ? 24 : $rowData['category_id'];

            // Convertir fechas usando Carbon
            $registeredAt = empty($rowData['registered_at'])
                ? null
                : Carbon::createFromFormat('d-m-Y', $rowData['registered_at'])->format('Y-m-d');

            $confirmedAt = empty($rowData['confirmed_at'])
                ? null
                : Carbon::createFromFormat('d-m-Y', $rowData['confirmed_at'])->format('Y-m-d');

            $organizations[] = [
                'id' => (string) Str::ulid(),
                'registration_id' => $rowData['registration_id'],
                'name' => $rowData['name'],
                /* 'rut' => $rut, */
                'rut_num' => empty($rowData['rut_num']) ? null : $rowData['rut_num'],
                'rut_vd' => empty($rowData['rut_vd']) ? null : $rowData['rut_vd'],
                'information_source' => $rowData['information_source'],
                'address' => empty($rowData['address']) ? null : $rowData['address'],
                'organization_type_id' => $rowData['organization_type_id'],
                'category_id' => $categoryId,
                'organization_state_id' => empty($rowData['organization_state_id']) ? null : $rowData['organization_state_id'],
                'registered_at' => $registeredAt,
                'confirmed_at' => $confirmedAt,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insertar en lotes para mejor performance
        $chunks = array_chunk($organizations, 500);
        foreach ($chunks as $chunk) {
            DB::table('organizations')->insert($chunk);
        }

        $this->command->info(sprintf('Inserted %d organizations', count($organizations)));
    }
}
