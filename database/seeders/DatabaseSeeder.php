<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Criterion;
use App\Models\CriteriaComparison;
use App\Models\Alternative;
use App\Models\AlternativeCriterion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrator KIP',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // 2. Seed Regular User for Testing
        $studentUser = User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('student123'),
                'role' => 'user',
            ]
        );

        // 3. Seed Criteria
        $criteriaData = [
            [
                'code' => 'C1',
                'name' => 'Penghasilan Orang Tua',
                'type' => 'cost', // The lower the income, the better (more eligible)
                'weight' => 0.40, // standard starting weight
            ],
            [
                'code' => 'C2',
                'name' => 'Rata-rata Rapor',
                'type' => 'benefit', // Higher grade is better
                'weight' => 0.20,
            ],
            [
                'code' => 'C3',
                'name' => 'Jumlah Tanggungan Orang Tua',
                'type' => 'benefit', // More dependents, more eligible
                'weight' => 0.15,
            ],
            [
                'code' => 'C4',
                'name' => 'Kondisi Rumah',
                'type' => 'cost', // Lower score (worse condition) means more eligible
                'weight' => 0.15,
            ],
            [
                'code' => 'C5',
                'name' => 'Prestasi Tambahan',
                'type' => 'benefit', // Higher achievements, better
                'weight' => 0.10,
            ],
        ];

        $criteria = [];
        foreach ($criteriaData as $data) {
            $criteria[] = Criterion::updateOrCreate(['code' => $data['code']], $data);
        }

        // 4. Seed Criteria Comparisons (AHP Matrix default consistent values)
        // We will seed standard values that yield approximately the weights above
        // C1 (Income) is highly important.
        $matrixValues = [
            'C1' => ['C1' => 1.0, 'C2' => 2.0, 'C3' => 3.0, 'C4' => 3.0, 'C5' => 4.0],
            'C2' => ['C1' => 0.5, 'C2' => 1.0, 'C3' => 1.5, 'C4' => 1.5, 'C5' => 2.0],
            'C3' => ['C1' => 0.33, 'C2' => 0.67, 'C3' => 1.0, 'C4' => 1.0, 'C5' => 1.5],
            'C4' => ['C1' => 0.33, 'C2' => 0.67, 'C3' => 1.0, 'C4' => 1.0, 'C5' => 1.5],
            'C5' => ['C1' => 0.25, 'C2' => 0.5, 'C3' => 0.67, 'C4' => 0.67, 'C5' => 1.0],
        ];

        foreach ($criteria as $c1) {
            foreach ($criteria as $c2) {
                $val = $matrixValues[$c1->code][$c2->code] ?? 1.0;
                CriteriaComparison::updateOrCreate(
                    [
                        'criteria_id_1' => $c1->id,
                        'criteria_id_2' => $c2->id,
                    ],
                    [
                        'value' => $val
                    ]
                );
            }
        }

        // 5. Seed Sample Alternatives
        $sampleAlternatives = [
            [
                'name' => 'Budi Santoso',
                'nisn' => '1234567890',
                'email' => 'student@example.com',
                'phone' => '081234567890',
                'user_id' => $studentUser->id,
                'scores' => [
                    'C1' => 1500000, // Parent's income
                    'C2' => 85.5,    // Average grade
                    'C3' => 3,       // Dependents
                    'C4' => 2,       // House condition (1: Poor, 5: Luxury)
                    'C5' => 2,       // Achievement score (1-5)
                ]
            ],
            [
                'name' => 'Siti Aminah',
                'nisn' => '0987654321',
                'email' => 'siti@example.com',
                'phone' => '082198765432',
                'user_id' => null,
                'scores' => [
                    'C1' => 800000,
                    'C2' => 88.0,
                    'C3' => 5,
                    'C4' => 1,
                    'C5' => 1,
                ]
            ],
            [
                'name' => 'Rian Hidayat',
                'nisn' => '1122334455',
                'email' => 'rian@example.com',
                'phone' => '085711223344',
                'user_id' => null,
                'scores' => [
                    'C1' => 3000000,
                    'C2' => 92.5,
                    'C3' => 2,
                    'C4' => 4,
                    'C5' => 4,
                ]
            ],
            [
                'name' => 'Dewi Lestari',
                'nisn' => '5544332211',
                'email' => 'dewi@example.com',
                'phone' => '089955443322',
                'user_id' => null,
                'scores' => [
                    'C1' => 1200000,
                    'C2' => 80.0,
                    'C3' => 4,
                    'C4' => 2,
                    'C5' => 3,
                ]
            ],
        ];

        foreach ($sampleAlternatives as $altData) {
            $alt = Alternative::updateOrCreate(
                ['nisn' => $altData['nisn']],
                [
                    'name' => $altData['name'],
                    'email' => $altData['email'],
                    'phone' => $altData['phone'],
                    'user_id' => $altData['user_id'],
                ]
            );

            foreach ($criteria as $criterion) {
                $scoreValue = $altData['scores'][$criterion->code] ?? 0;
                AlternativeCriterion::updateOrCreate(
                    [
                        'alternative_id' => $alt->id,
                        'criterion_id' => $criterion->id,
                    ],
                    [
                        'value' => $scoreValue,
                    ]
                );
            }
        }
    }
}
