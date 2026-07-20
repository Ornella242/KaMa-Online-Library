<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SponsorshipPlan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SponsorshipPlanController extends Controller
{
    public function index(Request $request)
    {
        $plans = SponsorshipPlan::query()
            ->withCount('sponsorships')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                if ($request->status === 'active') {
                    $query->where('active', true);
                }
                if ($request->status === 'inactive') {
                    $query->where('active', false);
                }
            })
            ->orderBy('duration_days')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => SponsorshipPlan::count(),
            'active' => SponsorshipPlan::where('active', true)->count(),
            'inactive' => SponsorshipPlan::where('active', false)->count(),
            'avg_price' => (float) SponsorshipPlan::avg('price'),
        ];

        return view('admin.sponsorship-plans.index', compact('plans', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:sponsorship_plans,name'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:365'],
            'price' => ['required', 'numeric', 'min:0'],
            'active' => ['nullable', 'boolean'],
        ]);

        SponsorshipPlan::query()->create([
            'name' => $validated['name'],
            'duration_days' => $validated['duration_days'],
            'price' => $validated['price'],
            'active' => $request->boolean('active', true),
        ]);

        return redirect()
            ->route('admin.sponsorship-plans.index')
            ->with('success', 'Formule de sponsoring créée.');
    }

    public function update(Request $request, SponsorshipPlan $sponsorship_plan)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sponsorship_plans', 'name')->ignore($sponsorship_plan->id),
            ],
            'duration_days' => ['required', 'integer', 'min:1', 'max:365'],
            'price' => ['required', 'numeric', 'min:0'],
            'active' => ['nullable', 'boolean'],
        ]);

        $sponsorship_plan->update([
            'name' => $validated['name'],
            'duration_days' => $validated['duration_days'],
            'price' => $validated['price'],
            'active' => $request->boolean('active'),
        ]);

        return redirect()
            ->route('admin.sponsorship-plans.index')
            ->with('success', 'Formule mise à jour.');
    }

    public function destroy(SponsorshipPlan $sponsorship_plan)
    {
        if ($sponsorship_plan->sponsorships()->exists()) {
            return back()->with('error', 'Impossible de supprimer une formule déjà utilisée. Désactivez-la plutôt.');
        }

        $sponsorship_plan->delete();

        return redirect()
            ->route('admin.sponsorship-plans.index')
            ->with('success', 'Formule supprimée.');
    }
}
