<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    /**
     * Display a listing of the user's documents.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'Youth') {
            abort(403, 'Unauthorized access');
        }

        $documents = Document::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('youth.documents', compact('documents'));
    }

    /**
     * Store a newly uploaded document.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'Youth') {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'type' => ['required', 'string', Rule::in(['id', 'transcript', 'certificate', 'other'])],
            'document' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:10240', // 10MB max
            ],
        ]);

        $file = $request->file('document');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documents/' . $user->id, $filename, 'public');

        Document::create([
            'user_id' => $user->id,
            'type' => $validated['type'],
            'name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully! It will be reviewed by an administrator.');
    }

    /**
     * Download a document.
     */
    public function download($id)
    {
        $user = Auth::user();
        $document = Document::findOrFail($id);

        // Only allow user to download their own documents, or admin to download any
        if ($document->user_id !== $user->id && $user->role !== 'Admin') {
            abort(403, 'Unauthorized access');
        }

        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($document->file_path, $document->name);
    }

    /**
     * Delete a document.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $document = Document::findOrFail($id);

        // Only allow user to delete their own documents
        if ($document->user_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }

    /**
     * Admin: View all pending documents for verification.
     */
    public function adminIndex()
    {
        $admin = Auth::user();

        if ($admin->role !== 'Admin') {
            abort(403, 'Unauthorized access');
        }

        $documents = Document::with(['user', 'verifier'])
            ->latest()
            ->get();

        $pending = $documents->where('status', 'pending');
        $verified = $documents->where('status', 'verified');
        $rejected = $documents->where('status', 'rejected');

        return view('admin.documents', compact('documents', 'pending', 'verified', 'rejected'));
    }

    /**
     * Admin: Verify a document.
     */
    public function verify($id)
    {
        $admin = Auth::user();

        if ($admin->role !== 'Admin') {
            abort(403, 'Unauthorized access');
        }

        $document = Document::findOrFail($id);
        $document->update([
            'status' => 'verified',
            'verified_by' => $admin->id,
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Document verified successfully.');
    }

    /**
     * Admin: Reject a document.
     */
    public function reject(Request $request, $id)
    {
        $admin = Auth::user();

        if ($admin->role !== 'Admin') {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $document = Document::findOrFail($id);
        $document->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'verified_by' => $admin->id,
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Document rejected.');
    }
}

