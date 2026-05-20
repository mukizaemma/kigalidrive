<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ListingAgreementSignature extends Model
{
    protected $fillable = [
        'signable_id',
        'signable_type',
        'owner_signature_path',
        'signed_at',
        'template_version_at',
        'signer_ip',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'template_version_at' => 'datetime',
    ];

    public function signable(): MorphTo
    {
        return $this->morphTo();
    }

    public function isCurrentForTemplate(?ListingAgreementTemplate $template): bool
    {
        if (! $template || ! $this->signed_at || ! $this->owner_signature_path || ! $this->template_version_at) {
            return false;
        }

        return abs($template->updated_at->getTimestamp() - $this->template_version_at->getTimestamp()) < 2;
    }
}
