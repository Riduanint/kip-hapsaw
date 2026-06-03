<?php

namespace App\Services;

class SAWService
{
    /**
     * Compute SAW Normalization and Preference Scores
     * 
     * @param array $alternatives Array of alternatives with their criterion values.
     *                            Format: [
     *                                ['id' => 1, 'name' => 'Budi', 'scores' => [crit_id1 => val1, crit_id2 => val2]],
     *                                ...
     *                            ]
     * @param array $criteria Array of criteria with their types and weights.
     *                        Format: [
     *                            ['id' => 1, 'type' => 'benefit', 'weight' => 0.40],
     *                            ...
     *                        ]
     * @return array
     */
    public function calculate(array $alternatives, array $criteria): array
    {
        if (empty($alternatives) || empty($criteria)) {
            return [];
        }

        // 1. Find min and max values for each criterion
        $minMax = [];
        foreach ($criteria as $criterion) {
            $critId = $criterion['id'];
            $values = [];
            foreach ($alternatives as $alt) {
                $values[] = floatval($alt['scores'][$critId] ?? 0.0);
            }

            $minMax[$critId] = [
                'min' => !empty($values) ? min($values) : 0.0,
                'max' => !empty($values) ? max($values) : 1.0,
            ];
        }

        // 2. Normalize matrix
        $normalizedMatrix = [];
        foreach ($alternatives as $alt) {
            $altId = $alt['id'];
            $normalizedMatrix[$altId] = [];

            foreach ($criteria as $criterion) {
                $critId = $criterion['id'];
                $val = floatval($alt['scores'][$critId] ?? 0.0);
                $type = $criterion['type'];

                $normVal = 0.0;
                if ($type === 'benefit') {
                    $max = $minMax[$critId]['max'];
                    $normVal = $max != 0 ? $val / $max : 0.0;
                } else { // cost
                    $min = $minMax[$critId]['min'];
                    $normVal = $val != 0 ? $min / $val : 0.0;
                }

                $normalizedMatrix[$altId][$critId] = $normVal;
            }
        }

        // 3. Compute final scores (preference value V_i)
        $results = [];
        foreach ($alternatives as $alt) {
            $altId = $alt['id'];
            $score = 0.0;

            foreach ($criteria as $criterion) {
                $critId = $criterion['id'];
                $weight = floatval($criterion['weight']);
                $normVal = $normalizedMatrix[$altId][$critId] ?? 0.0;
                $score += $normVal * $weight;
            }

            $results[] = [
                'id' => $alt['id'],
                'name' => $alt['name'],
                'nisn' => $alt['nisn'],
                'scores' => $alt['scores'],
                'normalized' => $normalizedMatrix[$altId],
                'final_score' => $score,
            ];
        }

        // 4. Sort results by final score descending
        usort($results, function ($a, $b) {
            if ($a['final_score'] == $b['final_score']) {
                return 0;
            }
            return ($a['final_score'] < $b['final_score']) ? 1 : -1;
        });

        // 5. Add ranks
        foreach ($results as $index => &$res) {
            $res['rank'] = $index + 1;
        }

        return [
            'min_max' => $minMax,
            'normalized_matrix' => $normalizedMatrix,
            'ranked_results' => $results,
        ];
    }
}
