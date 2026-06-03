<?php

namespace App\Services;

class AHPService
{
    // Random Index (RI) table for AHP
    private static $randomIndex = [
        1 => 0.00,
        2 => 0.00,
        3 => 0.58,
        4 => 0.90,
        5 => 1.12,
        6 => 1.24,
        7 => 1.32,
        8 => 1.41,
        9 => 1.45,
        10 => 1.49,
    ];

    /**
     * Compute AHP Weights and Consistency Ratio
     * 
     * @param array $criteriaIds List of criteria IDs
     * @param array $comparisons Pairwise comparisons map [$id1][$id2] => value
     * @return array
     */
    public function calculate(array $criteriaIds, array $comparisons): array
    {
        $n = count($criteriaIds);
        if ($n === 0) {
            return [
                'weights' => [],
                'lambda_max' => 0,
                'ci' => 0,
                'cr' => 0,
                'is_consistent' => true
            ];
        }

        if ($n <= 2) {
            // For 1 or 2 criteria, they are always consistent
            $weights = [];
            foreach ($criteriaIds as $id) {
                $weights[$id] = 1.0 / $n;
            }
            return [
                'weights' => $weights,
                'lambda_max' => $n,
                'ci' => 0,
                'cr' => 0,
                'is_consistent' => true
            ];
        }

        // 1. Build the matrix
        $matrix = [];
        foreach ($criteriaIds as $i => $id1) {
            $matrix[$id1] = [];
            foreach ($criteriaIds as $j => $id2) {
                if ($id1 == $id2) {
                    $matrix[$id1][$id2] = 1.0;
                } else {
                    // Try to get direct comparison or reciprocal
                    if (isset($comparisons[$id1][$id2])) {
                        $matrix[$id1][$id2] = floatval($comparisons[$id1][$id2]);
                    } elseif (isset($comparisons[$id2][$id1]) && $comparisons[$id2][$id1] != 0) {
                        $matrix[$id1][$id2] = 1.0 / floatval($comparisons[$id2][$id1]);
                    } else {
                        $matrix[$id1][$id2] = 1.0; // default to equal importance
                    }
                }
            }
        }

        // 2. Sum the columns
        $colSums = [];
        foreach ($criteriaIds as $id2) {
            $sum = 0.0;
            foreach ($criteriaIds as $id1) {
                $sum += $matrix[$id1][$id2];
            }
            $colSums[$id2] = $sum;
        }

        // 3. Normalize matrix and calculate weights (row averages)
        $weights = [];
        $normalizedMatrix = [];
        foreach ($criteriaIds as $id1) {
            $rowSum = 0.0;
            $normalizedMatrix[$id1] = [];
            foreach ($criteriaIds as $id2) {
                $normVal = $colSums[$id2] != 0 ? $matrix[$id1][$id2] / $colSums[$id2] : 0;
                $normalizedMatrix[$id1][$id2] = $normVal;
                $rowSum += $normVal;
            }
            $weights[$id1] = $rowSum / $n;
        }

        // 4. Calculate Consistency
        // Weighted sum vector: matrix * weightVector
        $weightedSum = [];
        foreach ($criteriaIds as $id1) {
            $sum = 0.0;
            foreach ($criteriaIds as $id2) {
                $sum += $matrix[$id1][$id2] * $weights[$id2];
            }
            $weightedSum[$id1] = $sum;
        }

        // Consistency vector: weightedSum / weight
        $consistencyVector = [];
        $lambdaMaxSum = 0.0;
        foreach ($criteriaIds as $id) {
            $consistencyVector[$id] = $weights[$id] != 0 ? $weightedSum[$id] / $weights[$id] : 0;
            $lambdaMaxSum += $consistencyVector[$id];
        }

        $lambdaMax = $lambdaMaxSum / $n;
        $ci = ($lambdaMax - $n) / ($n - 1);
        
        $ri = self::$randomIndex[$n] ?? 1.49;
        $cr = $ri != 0 ? $ci / $ri : 0;
        $isConsistent = $cr < 0.1;

        return [
            'matrix' => $matrix,
            'col_sums' => $colSums,
            'normalized_matrix' => $normalizedMatrix,
            'weights' => $weights,
            'lambda_max' => $lambdaMax,
            'ci' => $ci,
            'cr' => $cr,
            'is_consistent' => $isConsistent
        ];
    }
}
