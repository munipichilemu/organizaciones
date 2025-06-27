<?php

namespace Database\Seeders;

use App\Models\Leader;
use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LeadersSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = storage_path('app/imports/leaders.csv');

        if (! File::exists($csvFile)) {
            $this->command->error('CSV file not found: '.$csvFile);

            return;
        }

        $csvData = File::get($csvFile);
        $rows = explode("\n", $csvData);
        $header = str_getcsv(array_shift($rows));

        $processedLeaders = [];
        $leaderOrganizations = [];

        foreach ($rows as $row) {
            if (empty(trim($row))) {
                continue;
            }

            $data = str_getcsv($row);
            $rowData = array_combine($header, $data);

            // Skip if essential data is missing
            if (empty($rowData['name']) || empty($rowData['rut_num'])) {
                continue;
            }

            $rutNum = $rowData['rut_num'];

            // Check if leader already processed in this batch
            if (! isset($processedLeaders[$rutNum])) {
                $processedLeaders[$rutNum] = [
                    'id' => (string) Str::ulid(),
                    'name' => $rowData['name'],
                    'rut_num' => $rutNum,
                    'rut_vd' => $rowData['rut_vd'] ?? null,
                    'address' => empty($rowData['address']) ? null : $rowData['address'],
                    'phone' => empty($rowData['phone']) ? null : $rowData['phone'],
                    'email' => empty($rowData['email']) ? null : $rowData['email'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Store organization relationship
            $leaderOrganizations[] = [
                'leader_rut_num' => $rutNum,
                'organization_registration_id' => $rowData['organization'],
                'member_position_slug' => $rowData['member_position'],
            ];
        }

        // Insert or get existing leaders
        $leaderIds = [];
        foreach ($processedLeaders as $rutNum => $leaderData) {
            $existingLeader = DB::table('leaders')
                ->where('rut_num', $rutNum)
                ->whereNull('deleted_at')
                ->first();

            if ($existingLeader) {
                $leaderIds[$rutNum] = $existingLeader->id;
            } else {
                DB::table('leaders')->insert($leaderData);
                $leaderIds[$rutNum] = $leaderData['id'];
            }
        }

        // Get organization and position mappings
        $organizations = DB::table('organizations')
            ->whereNull('deleted_at')
            ->pluck('id', 'registration_id')
            ->toArray();

        $positions = DB::table('member_positions')
            ->pluck('id', 'slug')
            ->toArray();

        // Prepare leader-organization relationships
        $relationships = [];
        foreach ($leaderOrganizations as $relation) {
            $organizationId = $organizations[$relation['organization_registration_id']] ?? null;
            $positionId = $positions[$relation['member_position_slug']] ?? null;
            $leaderId = $leaderIds[$relation['leader_rut_num']] ?? null;

            if ($organizationId && $positionId && $leaderId) {
                // Check if relationship already exists
                $exists = DB::table('leader_organization')
                    ->where('leader_id', $leaderId)
                    ->where('organization_id', $organizationId)
                    ->whereNull('deleted_at')
                    ->exists();

                if (! $exists) {
                    $relationships[] = [
                        'leader_id' => $leaderId,
                        'organization_id' => $organizationId,
                        'member_position_id' => $positionId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Insert relationships in batches
        if (! empty($relationships)) {
            $chunks = array_chunk($relationships, 500);
            foreach ($chunks as $chunk) {
                DB::table('leader_organization')->insert($chunk);
            }
        }

        $this->command->info(sprintf(
            'Processed %d leaders and created %d organization relationships',
            count($processedLeaders),
            count($relationships)
        ));
    }
}
