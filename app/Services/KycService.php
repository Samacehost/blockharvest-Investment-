<?php

namespace App\Services;

use App\Models\KycSubmission;
use App\Models\KycDocument;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class KycService
{
    /**
     * Submit KYC documents for verification
     */
    public static function submit(User $user, string $idType, ?string $idNumber, array $files): KycSubmission
    {
        $submission = KycSubmission::create([
            'user_id' => $user->id,
            'id_type' => $idType,
            'id_number' => $idNumber,
            'status' => 'pending',
        ]);

        foreach ($files as $docType => $file) {
            if ($file instanceof UploadedFile) {
                $path = $file->store("private/kyc/{$user->id}");

                KycDocument::create([
                    'kyc_submission_id' => $submission->id,
                    'document_type' => $docType,
                    'file_path' => $path,
                    'original_filename' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                ]);
            }
        }

        $user->update(['kyc_status' => 'pending']);

        return $submission;
    }

    /**
     * Generate temporary signed URL for private KYC document previewing
     */
    public static function getSignedUrl(KycDocument $document, int $expirationMinutes = 15): string
    {
        return URL::temporarySignedRoute(
            'admin.kyc.download',
            now()->addMinutes($expirationMinutes),
            ['document' => $document->id]
        );
    }

    /**
     * Approve a KYC submission
     */
    public static function approve(KycSubmission $submission, User $adminUser): void
    {
        $submission->update([
            'status' => 'approved',
            'reviewed_by_admin_id' => $adminUser->id,
            'reviewed_at' => now(),
        ]);

        $submission->user->update(['kyc_status' => 'approved']);
    }

    /**
     * Reject a KYC submission with reason
     */
    public static function reject(KycSubmission $submission, User $adminUser, string $reason): void
    {
        $submission->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'reviewed_by_admin_id' => $adminUser->id,
            'reviewed_at' => now(),
        ]);

        $submission->user->update(['kyc_status' => 'rejected']);
    }
}
