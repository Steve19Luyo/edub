# SRS COMPLIANCE IMPLEMENTATION PLAN

**Status**: Implementation In Progress  
**Last Updated**: Latest  
**Priority**: Complete all FR requirements

---

## ✅ COMPLETED IMPLEMENTATIONS

### 1. Database Migrations Created
- ✅ `add_eligibility_criteria_to_opportunities_table.php` - Adds eligibility fields
- ✅ `create_documents_table.php` - Creates documents table

### 2. Models Created/Updated
- ✅ `Document` model - Complete with relationships and helper methods
- ✅ `Certificate` model - Complete with certificate number generation
- ✅ `User` model - Added documents relationship
- ✅ `Opportunity` model - Added eligibility criteria fields

### 3. Controllers Created
- ✅ `DocumentController` - Complete with upload, download, verify, reject

---

## 🚧 IN PROGRESS / TO BE IMPLEMENTED

### Phase 1: Critical Features (Immediate)

#### 1. Document Upload System (FR-03)
**Status**: 70% Complete

**Remaining Tasks**:
- [ ] Create `resources/views/youth/documents.blade.php` view
- [ ] Add document upload form to youth profile edit page
- [ ] Create admin document verification interface
- [ ] Add routes for document management
- [ ] Configure file storage (ensure `storage/app/public/documents` exists)
- [ ] Add document status indicators in UI

**Routes Needed**:
```php
Route::middleware(['auth', 'role:Youth'])->group(function () {
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');
});

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::post('/admin/documents/{id}/verify', [DocumentController::class, 'verify'])->name('admin.documents.verify');
    Route::post('/admin/documents/{id}/reject', [DocumentController::class, 'reject'])->name('admin.documents.reject');
});
```

#### 2. Eligibility Criteria (DR-02)
**Status**: 60% Complete

**Remaining Tasks**:
- [ ] Update `resources/views/organization/dashboard.blade.php` form
- [ ] Add eligibility fields to opportunity creation form
- [ ] Add eligibility fields to opportunity edit form (when created)
- [ ] Update `OpportunityController::store()` to save eligibility fields
- [ ] Create `OpportunityController::edit()` and `update()` methods
- [ ] Add eligibility filtering in `OpportunityController::list()`
- [ ] Add eligibility validation in `ApplicationController::apply()`
- [ ] Display eligibility requirements in opportunity views

#### 3. Certificate Generation (FR-07)
**Status**: 30% Complete

**Remaining Tasks**:
- [ ] Create Certificate generation service class
- [ ] Install PDF library (e.g., `barryvdh/laravel-dompdf`)
- [ ] Create certificate PDF template
- [ ] Add certificate generation to Application completion workflow
- [ ] Create certificate download/view functionality
- [ ] Add certificate verification system (public verification page)
- [ ] Update Application model to track completion
- [ ] Add "Mark as Completed" functionality for organizations

**Certificate Service Structure**:
```php
// app/Services/CertificateService.php
class CertificateService {
    public function generate(Application $application): Certificate
    public function generatePDF(Certificate $certificate): string
    public function verifyCertificateNumber(string $number): ?Certificate
}
```

#### 4. Intelligent Matching Engine (FR-04)
**Status**: 0% Complete

**Required Implementation**:
- [ ] Create `MatchingService` class
- [ ] Implement matching algorithm:
  - Skills matching (weight: 40%)
  - Education level matching (weight: 25%)
  - Availability matching (weight: 20%)
  - Location matching (weight: 15%)
- [ ] Add `getRecommendedOpportunities()` method to OpportunityController
- [ ] Create "Recommended for You" section in youth opportunities page
- [ ] Add matching score display
- [ ] Add filtering options (by skills, education, etc.)

**Matching Algorithm Logic**:
```php
// Calculate match score for each opportunity
$score = 0;
if (skills match) $score += 40;
if (education matches) $score += 25;
if (availability matches) $score += 20;
if (location matches) $score += 15;
return $score;
```

#### 5. Opportunity Edit/Publish (FR-05)
**Status**: 20% Complete

**Remaining Tasks**:
- [ ] Create `OpportunityController::edit()` method
- [ ] Create `OpportunityController::update()` method
- [ ] Create `resources/views/organization/edit-opportunity.blade.php`
- [ ] Add edit button to organization dashboard
- [ ] Add publish/unpublish functionality
- [ ] Add status management (draft/published/closed)
- [ ] Update opportunity list to filter by status
- [ ] Add routes for edit/update/publish

---

### Phase 2: High Priority Features

#### 6. Registration OTP (FR-02)
**Status**: 0% Complete

**Required Implementation**:
- [ ] Create OTP model/migration (or extend TwoFactorAuthentication)
- [ ] Modify `RegisteredUserController` to send OTP after registration
- [ ] Create OTP verification view
- [ ] Add mobile number field to registration
- [ ] Integrate SMS gateway (optional, can use email initially)
- [ ] Add account activation workflow
- [ ] Prevent login until account activated

#### 7. Admin Reporting Dashboard (FR-08)
**Status**: 10% Complete

**Required Implementation**:
- [ ] Create `AdminController::reports()` method
- [ ] Create `resources/views/admin/reports.blade.php`
- [ ] Add metrics:
  - Total users (youth/organizations)
  - Total opportunities
  - Total applications
  - Success rate
  - Certificates issued
  - Verification statistics
- [ ] Add charts/graphs (using Chart.js or similar)
- [ ] Add date range filtering
- [ ] Add export functionality (CSV/PDF)

#### 8. Activity Logging (DR-04)
**Status**: 0% Complete

**Required Implementation**:
- [ ] Create `activity_logs` migration
- [ ] Create `ActivityLog` model
- [ ] Create `ActivityLogService` class
- [ ] Add logging middleware or trait
- [ ] Log all user actions:
  - Registration, login, logout
  - Profile updates
  - Application submissions
  - Status changes
  - Document uploads
  - Opportunity creation/updates
- [ ] Create admin log viewer
- [ ] Add log retention (12 months)
- [ ] Add log export functionality

---

## 📝 ROUTES TO ADD

Add these routes to `routes/web.php`:

```php
// Document Routes
Route::middleware(['auth', 'role:Youth'])->group(function () {
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');
});

// Admin Document Verification
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/documents', [DocumentController::class, 'adminIndex'])->name('admin.documents.index');
    Route::post('/admin/documents/{id}/verify', [DocumentController::class, 'verify'])->name('admin.documents.verify');
    Route::post('/admin/documents/{id}/reject', [DocumentController::class, 'reject'])->name('admin.documents.reject');
});

// Opportunity Edit/Publish
Route::middleware(['auth', 'role:Organization'])->group(function () {
    Route::get('/opportunities/{id}/edit', [OpportunityController::class, 'edit'])->name('opportunities.edit');
    Route::put('/opportunities/{id}', [OpportunityController::class, 'update'])->name('opportunities.update');
    Route::post('/opportunities/{id}/publish', [OpportunityController::class, 'publish'])->name('opportunities.publish');
    Route::post('/opportunities/{id}/unpublish', [OpportunityController::class, 'unpublish'])->name('opportunities.unpublish');
});

// Certificate Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/certificates/{id}/download', [CertificateController::class, 'download'])->name('certificates.download');
    Route::get('/certificates/verify/{number}', [CertificateController::class, 'verify'])->name('certificates.verify'); // Public
});

// Matching/Recommendations
Route::middleware(['auth', 'role:Youth'])->group(function () {
    Route::get('/opportunities/recommended', [OpportunityController::class, 'recommended'])->name('opportunities.recommended');
});

// Admin Reports
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
});
```

---

## 🔧 CONFIGURATION NEEDED

### 1. File Storage Configuration

Ensure `config/filesystems.php` has:
```php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
],
```

Create symlink:
```bash
php artisan storage:link
```

### 2. PDF Generation Library

Add to `composer.json`:
```json
"require": {
    "barryvdh/laravel-dompdf": "^2.0"
}
```

Then:
```bash
composer require barryvdh/laravel-dompdf
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

---

## 📊 IMPLEMENTATION PRIORITY

1. **Week 1**: Document Upload + Eligibility Criteria
2. **Week 2**: Certificate Generation + Matching Engine
3. **Week 3**: Opportunity Edit/Publish + Registration OTP
4. **Week 4**: Admin Reporting + Activity Logging

---

## ✅ TESTING CHECKLIST

After each implementation:
- [ ] Test file upload (PDF, DOCX, JPG)
- [ ] Test document verification workflow
- [ ] Test eligibility filtering
- [ ] Test certificate generation
- [ ] Test matching algorithm accuracy
- [ ] Test opportunity edit/publish
- [ ] Test OTP verification flow
- [ ] Test admin reporting
- [ ] Test activity logging

---

**Next Steps**: Continue implementing Phase 1 features systematically.

