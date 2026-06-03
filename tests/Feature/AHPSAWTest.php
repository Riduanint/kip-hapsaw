<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\AHPService;
use App\Services\SAWService;

class AHPSAWTest extends TestCase
{
    public function test_ahp_service_calculates_correct_weights_and_consistency()
    {
        $ahpService = new AHPService();

        // 3 criteria
        $criteriaIds = [1, 2, 3];
        // C1 vs C2: 3, C1 vs C3: 5, C2 vs C3: 2
        $comparisons = [
            1 => [
                2 => 3.0,
                3 => 5.0,
            ],
            2 => [
                3 => 2.0,
            ]
        ];

        $result = $ahpService->calculate($criteriaIds, $comparisons);

        $this->assertArrayHasKey('weights', $result);
        $this->assertArrayHasKey('cr', $result);
        $this->assertTrue($result['is_consistent']);
        
        // Sum of weights should be 1
        $this->assertEqualsWithDelta(1.0, array_sum($result['weights']), 0.01);
    }

    public function test_saw_service_calculates_correct_scores_and_rankings()
    {
        $sawService = new SAWService();

        $criteria = [
            ['id' => 1, 'type' => 'cost', 'weight' => 0.6],     // C1 (e.g. Income)
            ['id' => 2, 'type' => 'benefit', 'weight' => 0.4],  // C2 (e.g. Grade)
        ];

        $alternatives = [
            [
                'id' => 101,
                'name' => 'Budi',
                'nisn' => '1111111111',
                'scores' => [
                    1 => 1000000.0, // cost: min is 1M, normalized = 1M/1M = 1.0
                    2 => 80.0,      // benefit: max is 90, normalized = 80/90 = 0.888
                ]
            ],
            [
                'id' => 102,
                'name' => 'Siti',
                'nisn' => '2222222222',
                'scores' => [
                    1 => 2000000.0, // cost: min is 1M, normalized = 1M/2M = 0.5
                    2 => 90.0,      // benefit: max is 90, normalized = 90/90 = 1.0
                ]
            ]
        ];

        $result = $sawService->calculate($alternatives, $criteria);

        // Budi: (1.0 * 0.6) + (0.8888 * 0.4) = 0.6 + 0.3555 = 0.9555
        // Siti: (0.5 * 0.6) + (1.0 * 0.4) = 0.3 + 0.4 = 0.70
        // Budi should be rank 1, Siti rank 2
        $this->assertEquals(101, $result['ranked_results'][0]['id']);
        $this->assertEquals(102, $result['ranked_results'][1]['id']);
        
        $this->assertEquals(1, $result['ranked_results'][0]['rank']);
        $this->assertEquals(2, $result['ranked_results'][1]['rank']);
    }
}
