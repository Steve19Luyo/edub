<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Opportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    // Youth applies for an opportunity
    public function apply($id, Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'Youth') {
            abort(403, 'Only youth can apply for opportunities.');
        }
        
        $opportunity = Opportunity::with(['organization', 'organization.user'])->findOrFail($id);
        
        // Ensure opportunity is from a verified organization
        if (!$opportunity->organization || !$opportunity->organization->user || !$opportunity->organization->user->verified) {
            return redirect()->back()->with('error', 'This opportunity is not available.');
        }

        // Ensure opportunity is published
        if ($opportunity->status !== 'published') {
            return redirect()->back()->with('error', 'This opportunity is not currently available.');
        }

        // Check eligibility criteria if set
        $youthProfile = \App\Models\YouthProfile::firstOrCreate(['user_id' => $user->id]);
        
        // Check age eligibility
        if ($opportunity->min_age || $opportunity->max_age) {
            if (!$youthProfile->birth_date) {
                return redirect()->back()->with('error', 'Please update your profile with your birth date to apply for this opportunity.');
            }
            $age = now()->diffInYears($youthProfile->birth_date);
            if (($opportunity->min_age && $age < $opportunity->min_age) || 
                ($opportunity->max_age && $age > $opportunity->max_age)) {
                return redirect()->back()->with('error', 'You do not meet the age requirements for this opportunity.');
            }
        }

        // Check education level eligibility
        if ($opportunity->required_education_level && $youthProfile->education_level !== $opportunity->required_education_level) {
            return redirect()->back()->with('error', 'You do not meet the education level requirement for this opportunity.');
        }

        // Check skills eligibility
        if ($opportunity->required_skills && is_array($opportunity->required_skills) && !empty($opportunity->required_skills)) {
            $userSkills = is_array($youthProfile->skills) ? $youthProfile->skills : [];
            $requiredSkills = array_map('strtolower', $opportunity->required_skills);
            $userSkillsLower = array_map('strtolower', $userSkills);
            $hasRequiredSkills = !empty(array_intersect($requiredSkills, $userSkillsLower));
            
            if (!$hasRequiredSkills) {
                return redirect()->back()->with('error', 'You do not have the required skills for this opportunity.');
            }
        }

        // Get or create youth profile (already done above, but keeping for clarity)
        // $youthProfile already created above

        // Prevent duplicate application
        $exists = Application::where('opportunity_id', $id)
            ->where('youth_profile_id', $youthProfile->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'You already applied for this opportunity.');
        }

        Application::create([
            'opportunity_id' => $id,
            'youth_profile_id' => $youthProfile->id,
            'status' => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Application submitted successfully!');
    }

    // Organization views applicants for their opportunities
    public function applicants()
    {
        $user = Auth::user();
        $organization = \App\Models\Organization::where('user_id', $user->id)->first();
        
        if (!$organization) {
            $applications = collect([]);
        } else {
            $applications = Application::with(['opportunity', 'youthProfile.user'])
                ->whereHas('opportunity', function ($q) use ($organization) {
                    $q->where('organization_id', $organization->id);
                })
                ->get();
        }

        return view('organization.applicants', compact('applications'));
    }

    // Organization updates application status
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);
        $application = Application::findOrFail($id);
        $application->status = $request->status;
        $application->save();

        return redirect()->back()->with('success', 'Application status updated!');
    }

    // Youth view: their applications
    public function myApplications()
    {
        $user = Auth::user();
        $youthProfile = \App\Models\YouthProfile::where('user_id', $user->id)->first();
        
        if (!$youthProfile) {
            $applications = collect([]);
        } else {
            $applications = Application::with(['opportunity', 'opportunity.organization', 'opportunity.organization.user'])
                ->where('youth_profile_id', $youthProfile->id)
                ->get();
        }
        
        return view('youth.applications', compact('applications'));
    }
}
