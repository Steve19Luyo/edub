<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'application_id',
        'certificate_number',
        'file_path',
        'issued_on',
    ];

    protected $casts = [
        'issued_on' => 'date',
    ];

    /**
     * Get the application this certificate belongs to.
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Generate a unique certificate number.
     */
    public static function generateCertificateNumber(): string
    {
        do {
            $number = 'EDU-' . date('Y') . '-' . strtoupper(substr(uniqid(), -8));
        } while (self::where('certificate_number', $number)->exists());

        return $number;
    }
}
