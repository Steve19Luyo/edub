<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpportunityController extends Controller
{
    // Organization view: all opportunities created by them
    public function index()
    {
        $user = Auth::user();

        // Defensive check — only organizations should be here
        if ($user->role !== 'Organization') {
            abort(403, 'Unauthorized');
        }

        // Get organization for this user
        $organization = \App\Models\Organization::where('user_id', $user->id)->first();
        
        if (!$organization) {
            $opportunities = collect([]);
        } else {
            $opportunities = Opportunity::where('organization_id', $organization->id)
                ->latest()
                ->get();
        }

        return view('organization.dashboard', compact('opportunities'));
    }

    // Create opportunity (Organization only)
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'Organization') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'deadline'    => 'required|date|after:today',
            'seats'       => 'required|integer|min:1',
            'min_age'     => 'nullable|integer|min:18|max:100',
            'max_age'     => 'nullable|integer|min:18|max:100|gte:min_age',
            'required_education_level' => 'nullable|string|max:100',
            'required_skills' => 'nullable|string',
            'preferred_location' => 'nullable|string|max:255',
        ], [
            'max_age.gte' => 'Maximum age must be greater than or equal to minimum age.',
        ]);

        // Get or create organization record for this user
        $organization = \App\Models\Organization::firstOrCreate(
            ['user_id' => $user->id],
            ['name' => $user->name, 'contact_email' => $user->email]
        );

        // Process required_skills if provided (convert comma-separated to array)
        $requiredSkills = null;
        if (!empty($validated['required_skills'])) {
            $skillsArray = array_map('trim', explode(',', $validated['required_skills']));
            $requiredSkills = array_filter($skillsArray);
        }

        Opportunity::create([
            'organization_id' => $organization->id,
            'title'           => $validated['title'],
            'description'     => $validated['description'],
            'deadline'        => $validated['deadline'],
            'available_slots' => $validated['seats'],
            'min_age'         => $validated['min_age'] ?? null,
            'max_age'         => $validated['max_age'] ?? null,
            'required_education_level' => $validated['required_education_level'] ?? null,
            'required_skills' => $requiredSkills,
            'preferred_location' => $validated['preferred_location'] ?? null,
            'status'          => 'draft', // Default to draft, organization can publish later
        ]);

        return redirect()->back()->with('success', 'Opportunity created successfully!');
    }

    // Youth view: list all verified opportunities
    public function list()
    {
        // Get opportunities from verified organizations that are published
        // Check verification via User model (since AdminController sets verified on User)
        $opportunities = Opportunity::with(['organization', 'organization.user'])
            ->whereHas('organization.user', function ($q) {
                $q->where('verified', true)->where('role', 'Organization');
            })
            ->where('status', 'published') // Only show published opportunities
            ->where('deadline', '>=', now()->toDateString()) // Only show opportunities with future deadlines
            ->latest()
            ->get();

        return view('youth.opportunities', compact('opportunities'));
    }

    // View single opportunity
    public function show($id)
    {
        $opportunity = Opportunity::with(['organization', 'organization.user'])->findOrFail($id);
        
        // Ensure opportunity is published or user is the organization owner
        $user = Auth::user();
        $isOwner = false;
        
        if ($user && $user->role === 'Organization') {
            $organization = \App\Models\Organization::where('user_id', $user->id)->first();
            if ($organization && $opportunity->organization_id === $organization->id) {
                $isOwner = true;
            }
        }
        
        // If not owner and not published, don't show
        if (!$isOwner && $opportunity->status !== 'published') {
            abort(404, 'Opportunity not found');
        }
        
        return view('show', compact('opportunity'));
    }
}
