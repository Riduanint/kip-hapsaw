<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criterion;
use App\Models\AlternativeCriterion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Find alternative record related to this user
        $alternative = Alternative::where('user_id', $user->id)->first();

        if (!$alternative) {
            // Self-repair if alternative doesn't exist for some reason
            $alternative = Alternative::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'nisn' => 'KIP-' . rand(10000000, 99999999),
                'email' => $user->email,
                'status' => 'pending'
            ]);

            $criteria = Criterion::all();
            foreach ($criteria as $criterion) {
                AlternativeCriterion::create([
                    'alternative_id' => $alternative->id,
                    'criterion_id' => $criterion->id,
                    'value' => 0.0,
                ]);
            }
        }

        return view('user.dashboard', compact('user', 'alternative'));
    }

    public function application()
    {
        $user = Auth::user();
        $alternative = Alternative::where('user_id', $user->id)->firstOrFail();
        $criteria = Criterion::orderBy('code', 'asc')->get();
        
        // Load criteria values for this alternative
        $scores = [];
        foreach ($alternative->criteria as $c) {
            $scores[$c->id] = $c->pivot->value;
        }

        return view('user.application', compact('user', 'alternative', 'criteria', 'scores'));
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();
        $alternative = Alternative::where('user_id', $user->id)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'scores' => 'required|array',
        ]);

        DB::transaction(function () use ($request, $user, $alternative) {
            // Update User Profile
            $user->update([
                'name' => $request->name,
            ]);

            // Update Alternative Profile
            $alternative->update([
                'name' => $request->name,
                'phone' => $request->phone,
            ]);

            // Update Criteria Values
            $scores = $request->input('scores', []);
            foreach ($scores as $critId => $val) {
                AlternativeCriterion::updateOrCreate(
                    [
                        'alternative_id' => $alternative->id,
                        'criterion_id' => $critId
                    ],
                    [
                        'value' => floatval($val)
                    ]
                );
            }
        });

        return redirect()->route('user.application', ['sk' => request('sk')])->with('success', 'Profil dan berkas persyaratan pendaftaran KIP Anda berhasil diperbarui.');
    }
}
