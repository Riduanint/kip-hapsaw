<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Criterion;
use App\Models\CriteriaComparison;
use App\Models\Alternative;
use App\Models\AlternativeCriterion;
use App\Services\AHPService;
use App\Services\SAWService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    protected $ahpService;
    protected $sawService;

    public function __construct(AHPService $ahpService, SAWService $sawService)
    {
        $this->ahpService = $ahpService;
        $this->sawService = $sawService;
    }

    public function dashboard()
    {
        $totalCriteria = Criterion::count();
        $totalAlternatives = Alternative::count();
        $totalUsers = User::where('role', 'user')->count();

        // Get Top 5 Alternatives based on rank
        $topAlternatives = Alternative::whereNotNull('rank')
            ->orderBy('rank', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('totalCriteria', 'totalAlternatives', 'totalUsers', 'topAlternatives'));
    }

    // ==========================================
    // CRITERIA CRUD
    // ==========================================
    public function criteriaIndex()
    {
        $criteria = Criterion::orderBy('code', 'asc')->get();
        return view('admin.criteria', compact('criteria'));
    }

    public function criteriaStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:criteria,code|max:10',
            'name' => 'required|string|max:255',
            'type' => 'required|in:benefit,cost',
        ]);

        DB::transaction(function () use ($request) {
            $criterion = Criterion::create($request->only('code', 'name', 'type'));

            // Re-initialize AHP comparisons for the new criterion
            $allCriteria = Criterion::all();
            foreach ($allCriteria as $other) {
                // Self comparison
                if ($other->id == $criterion->id) {
                    CriteriaComparison::updateOrCreate(
                        ['criteria_id_1' => $criterion->id, 'criteria_id_2' => $criterion->id],
                        ['value' => 1.0]
                    );
                } else {
                    // C_new with C_other
                    CriteriaComparison::updateOrCreate(
                        ['criteria_id_1' => $criterion->id, 'criteria_id_2' => $other->id],
                        ['value' => 1.0]
                    );
                    // C_other with C_new
                    CriteriaComparison::updateOrCreate(
                        ['criteria_id_1' => $other->id, 'criteria_id_2' => $criterion->id],
                        ['value' => 1.0]
                    );
                }
            }

            // Initialize score 0 for all alternatives for this new criterion
            $alternatives = Alternative::all();
            foreach ($alternatives as $alt) {
                AlternativeCriterion::updateOrCreate(
                    ['alternative_id' => $alt->id, 'criterion_id' => $criterion->id],
                    ['value' => 0.0]
                );
            }
        });

        return redirect()->route('admin.criteria', ['sk' => request('sk')])->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function criteriaUpdate(Request $request, $id)
    {
        $criterion = Criterion::findOrFail($id);
        $request->validate([
            'code' => 'required|string|max:10|unique:criteria,code,' . $id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:benefit,cost',
        ]);

        $criterion->update($request->only('code', 'name', 'type'));

        return redirect()->route('admin.criteria', ['sk' => request('sk')])->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function criteriaDestroy($id)
    {
        $criterion = Criterion::findOrFail($id);
        $criterion->delete(); // cascading handled by DB foreign keys where defined

        return redirect()->route('admin.criteria', ['sk' => request('sk')])->with('success', 'Kriteria berhasil dihapus.');
    }

    // ==========================================
    // AHP PAIRWISE COMPARISONS
    // ==========================================
    public function ahpIndex()
    {
        $criteria = Criterion::orderBy('code', 'asc')->get();
        $n = count($criteria);

        // Fetch existing comparisons
        $comparisonsRaw = CriteriaComparison::all();
        $comparisons = [];
        foreach ($comparisonsRaw as $comp) {
            $comparisons[$comp->criteria_id_1][$comp->criteria_id_2] = $comp->value;
        }

        // Run calculation to show current weights & consistency status
        $ahpResult = $this->ahpService->calculate($criteria->pluck('id')->toArray(), $comparisons);

        return view('admin.ahp', compact('criteria', 'comparisons', 'ahpResult'));
    }

    public function ahpUpdate(Request $request)
    {
        $criteria = Criterion::orderBy('code', 'asc')->get();
        $matrix = $request->input('matrix', []);

        DB::transaction(function () use ($criteria, $matrix) {
            foreach ($criteria as $c1) {
                foreach ($criteria as $c2) {
                    if ($c1->id == $c2->id) {
                        CriteriaComparison::updateOrCreate(
                            ['criteria_id_1' => $c1->id, 'criteria_id_2' => $c2->id],
                            ['value' => 1.0]
                        );
                    } elseif ($c1->id < $c2->id) {
                        $val = isset($matrix[$c1->id][$c2->id]) ? floatval($matrix[$c1->id][$c2->id]) : 1.0;
                        if ($val <= 0) $val = 1.0;

                        // Save direct comparison (upper triangle)
                        CriteriaComparison::updateOrCreate(
                            ['criteria_id_1' => $c1->id, 'criteria_id_2' => $c2->id],
                            ['value' => $val]
                        );

                        // Save reciprocal comparison (lower triangle)
                        CriteriaComparison::updateOrCreate(
                            ['criteria_id_1' => $c2->id, 'criteria_id_2' => $c1->id],
                            ['value' => 1.0 / $val]
                        );
                    }
                }
            }

            // Fetch newly saved comparisons to calculate weights
            $comparisonsRaw = CriteriaComparison::all();
            $comparisons = [];
            foreach ($comparisonsRaw as $comp) {
                $comparisons[$comp->criteria_id_1][$comp->criteria_id_2] = $comp->value;
            }

            $ahpResult = $this->ahpService->calculate($criteria->pluck('id')->toArray(), $comparisons);

            // If the matrix is consistent, update criteria weights in DB
            if ($ahpResult['is_consistent']) {
                foreach (Criterion::all() as $c) {
                    $c->weight = $ahpResult['weights'][$c->id] ?? 0.0;
                    $c->save();
                }
            }
        });

        // We check if it is consistent
        $comparisonsRaw = CriteriaComparison::all();
        $comparisons = [];
        foreach ($comparisonsRaw as $comp) {
            $comparisons[$comp->criteria_id_1][$comp->criteria_id_2] = $comp->value;
        }
        $ahpResult = $this->ahpService->calculate($criteria->pluck('id')->toArray(), $comparisons);

        if (!$ahpResult['is_consistent']) {
            return redirect()->route('admin.ahp', ['sk' => request('sk')])->with('warning', 'Matriks disimpan, tetapi Nilai CR = ' . round($ahpResult['cr'], 4) . ' (>= 0.1). Matriks tidak konsisten! Silakan perbaiki input perbandingan Anda agar bobot dapat digunakan.');
        }

        return redirect()->route('admin.ahp', ['sk' => request('sk')])->with('success', 'Perbandingan AHP berhasil diperbarui dan matriks dinilai konsisten (CR = ' . round($ahpResult['cr'], 4) . '). Bobot kriteria diperbarui!');
    }

    // ==========================================
    // ALTERNATIVES CRUD
    // ==========================================
    public function alternativesIndex()
    {
        $alternatives = Alternative::orderBy('name', 'asc')->get();
        return view('admin.alternatives', compact('alternatives'));
    }

    public function alternativesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|max:20|unique:alternatives,nisn',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request) {
            $alt = Alternative::create($request->only('name', 'nisn', 'email', 'phone'));

            // Initialize score 0 for all criteria
            $criteria = Criterion::all();
            foreach ($criteria as $criterion) {
                AlternativeCriterion::create([
                    'alternative_id' => $alt->id,
                    'criterion_id' => $criterion->id,
                    'value' => 0.0,
                ]);
            }
        });

        return redirect()->route('admin.alternatives', ['sk' => request('sk')])->with('success', 'Alternatif berhasil ditambahkan.');
    }

    public function alternativesUpdate(Request $request, $id)
    {
        $alt = Alternative::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|max:20|unique:alternatives,nisn,' . $id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $alt->update($request->only('name', 'nisn', 'email', 'phone'));

        return redirect()->route('admin.alternatives', ['sk' => request('sk')])->with('success', 'Alternatif berhasil diperbarui.');
    }

    public function alternativesDestroy($id)
    {
        $alt = Alternative::findOrFail($id);
        $alt->delete();

        return redirect()->route('admin.alternatives', ['sk' => request('sk')])->with('success', 'Alternatif berhasil dihapus.');
    }

    // ==========================================
    // ALTERNATIVE CRITERIA SCORES
    // ==========================================
    public function scoresIndex()
    {
        $criteria = Criterion::orderBy('code', 'asc')->get();
        $alternatives = Alternative::with('criteria')->orderBy('name', 'asc')->get();

        return view('admin.scores', compact('criteria', 'alternatives'));
    }

    public function scoresUpdate(Request $request)
    {
        $scores = $request->input('scores', []);

        DB::transaction(function () use ($scores) {
            foreach ($scores as $altId => $critScores) {
                foreach ($critScores as $critId => $val) {
                    AlternativeCriterion::updateOrCreate(
                        ['alternative_id' => $altId, 'criterion_id' => $critId],
                        ['value' => floatval($val)]
                    );
                }
            }
        });

        return redirect()->route('admin.scores', ['sk' => request('sk')])->with('success', 'Nilai kriteria alternatif berhasil diperbarui.');
    }

    // ==========================================
    // SAW RANKING PROCESS
    // ==========================================
    public function sawIndex()
    {
        $criteria = Criterion::orderBy('code', 'asc')->get();
        $alternativesRaw = Alternative::with('criteria')->get();

        if ($criteria->isEmpty() || $alternativesRaw->isEmpty()) {
            return view('admin.saw', [
                'criteria' => $criteria,
                'alternatives' => $alternativesRaw,
                'sawResult' => null,
            ]);
        }

        // Format for SAW service
        $alternatives = [];
        foreach ($alternativesRaw as $alt) {
            $scores = [];
            foreach ($alt->criteria as $c) {
                $scores[$c->id] = $c->pivot->value;
            }
            $alternatives[] = [
                'id' => $alt->id,
                'name' => $alt->name,
                'nisn' => $alt->nisn,
                'scores' => $scores
            ];
        }

        $criteriaFormatted = [];
        foreach ($criteria as $c) {
            $criteriaFormatted[] = [
                'id' => $c->id,
                'code' => $c->code,
                'name' => $c->name,
                'type' => $c->type,
                'weight' => $c->weight
            ];
        }

        $sawResult = $this->sawService->calculate($alternatives, $criteriaFormatted);

        return view('admin.saw', compact('criteria', 'alternativesRaw', 'sawResult'));
    }

    public function sawLock(Request $request)
    {
        $quota = intval($request->input('quota', 2)); // Default quota of receivers, e.g. 2
        $criteria = Criterion::orderBy('code', 'asc')->get();
        $alternativesRaw = Alternative::with('criteria')->get();

        if ($criteria->isEmpty() || $alternativesRaw->isEmpty()) {
            return redirect()->route('admin.saw', ['sk' => request('sk')])->with('error', 'Kriteria atau Alternatif kosong.');
        }

        // Run calculations
        $alternatives = [];
        foreach ($alternativesRaw as $alt) {
            $scores = [];
            foreach ($alt->criteria as $c) {
                $scores[$c->id] = $c->pivot->value;
            }
            $alternatives[] = [
                'id' => $alt->id,
                'name' => $alt->name,
                'nisn' => $alt->nisn,
                'scores' => $scores
            ];
        }

        $criteriaFormatted = [];
        foreach ($criteria as $c) {
            $criteriaFormatted[] = [
                'id' => $c->id,
                'type' => $c->type,
                'weight' => $c->weight
            ];
        }

        $sawResult = $this->sawService->calculate($alternatives, $criteriaFormatted);

        DB::transaction(function () use ($sawResult, $quota) {
            foreach ($sawResult['ranked_results'] as $res) {
                $alt = Alternative::findOrFail($res['id']);
                
                $status = ($res['rank'] <= $quota) ? 'selected' : 'not_selected';

                $alt->update([
                    'final_score' => $res['final_score'],
                    'rank' => $res['rank'],
                    'status' => $status
                ]);
            }
        });

        return redirect()->route('admin.saw', ['sk' => request('sk')])->with('success', 'Perhitungan SAW berhasil dikunci! Status kelulusan alternatif diperbarui berdasarkan kuota penerima (' . $quota . ').');
    }
}
