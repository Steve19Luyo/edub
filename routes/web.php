<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\YouthProfileController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard route that redirects based on user role
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'Admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'Organization') {
        return redirect()->route('organization.dashboard');
    } elseif ($user->role === 'Youth') {
        return redirect()->route('opportunities.list');
    }

    // Default fallback
    return redirect()->route('profile.redirect');
})->name('dashboard');


// ======================= ORGANIZATION ROUTES =======================
Route::middleware(['auth', 'role:Organization'])->group(function () {

    // Dashboard - list organization’s own opportunities
    Route::get('/organization/dashboard', [OrganizationController::class, 'dashboard'])->name('organization.dashboard');

    // Post a new opportunity
    Route::post('/organization/opportunities', [OpportunityController::class, 'store'])->name('opportunities.store');

    // Edit and update opportunities
    Route::get('/opportunities/{id}/edit', [OpportunityController::class, 'edit'])->name('opportunities.edit');
    Route::put('/opportunities/{id}', [OpportunityController::class, 'update'])->name('opportunities.update');
    Route::post('/opportunities/{id}/publish', [OpportunityController::class, 'publish'])->name('opportunities.publish');
    Route::post('/opportunities/{id}/unpublish', [OpportunityController::class, 'unpublish'])->name('opportunities.unpublish');

    // View applicants for a specific opportunity
    Route::get('/organization/opportunity/{id}/applicants', [OrganizationController::class, 'viewApplicants'])->name('organization.applicants');

    // Update application status
    Route::post('/organization/application/{id}/status', [OrganizationController::class, 'updateApplicationStatus'])->name('organization.application.status');

    // Organization Profile
    Route::get('/organization/profile', [ProfileController::class, 'edit'])->name('organization.profile.edit');
    Route::patch('/organization/profile', [ProfileController::class, 'update'])->name('organization.profile.update');
});


// ========================== YOUTH ROUTES ==========================
Route::middleware(['auth', 'role:Youth'])->group(function () {

    // Youth Profile
    Route::get('/youth/profile', [YouthProfileController::class, 'index'])->name('youth.profile');
    Route::get('/youth/profile/edit', [YouthProfileController::class, 'edit'])->name('youth.profile.edit');
    Route::post('/youth/profile/update', [YouthProfileController::class, 'update'])->name('youth.profile.update');

    // Opportunities
    Route::get('/opportunities', [OpportunityController::class, 'list'])->name('opportunities.list');
    Route::get('/opportunities/{id}', [OpportunityController::class, 'show'])->name('opportunities.show');

    // Apply to opportunity
    Route::post('/opportunities/{id}/apply', [ApplicationController::class, 'apply'])->name('opportunity.apply');

    // My applications
    Route::get('/my-applications', [ApplicationController::class, 'myApplications'])->name('applications.my');

    // Documents
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');

    // Recommended opportunities (matching engine)
    Route::get('/opportunities/recommended', [OpportunityController::class, 'recommended'])->name('opportunities.recommended');

    // Certificates
    Route::get('/certificates', [CertificateController::class, 'index'])->name('youth.certificates');
});


// ============================ ADMIN ROUTES ============================
Route::middleware(['auth', 'role:Admin'])->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Verify or revoke organizations
    Route::post('/admin/verify/{id}', [AdminController::class, 'verifyOrg'])->name('admin.verifyOrg');
    Route::post('/admin/revoke/{id}', [AdminController::class, 'revokeOrg'])->name('admin.revokeOrg');

    // Document verification
    Route::get('/admin/documents', [DocumentController::class, 'adminIndex'])->name('admin.documents.index');
    Route::post('/admin/documents/{id}/verify', [DocumentController::class, 'verify'])->name('admin.documents.verify');
    Route::post('/admin/documents/{id}/reject', [DocumentController::class, 'reject'])->name('admin.documents.reject');

    // Reports dashboard
    Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');

    // Optional: View applications or opportunities
    // Route::get('/admin/opportunities', [AdminController::class, 'viewOpportunities'])->name('admin.opportunities');
});


// ===================== UNIVERSAL PROFILE REDIRECT =====================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', function () {
        $user = Auth::user();

        if ($user->role === 'Youth') {
            return redirect()->route('youth.profile');
        } elseif ($user->role === 'Organization') {
            return redirect()->route('organization.profile.edit');
        } elseif ($user->role === 'Admin') {
            return redirect()->route('admin.dashboard');
        }

        abort(403, 'Unauthorized');
    })->name('profile.redirect');
});

// ========================= PROFILE MANAGEMENT =========================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===================== PUBLIC CERTIFICATE VERIFICATION =====================
Route::get('/certificates/verify/{number}', [CertificateController::class, 'verify'])->name('certificates.verify');

require __DIR__.'/auth.php';
