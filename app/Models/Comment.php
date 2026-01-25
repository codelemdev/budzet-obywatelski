<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_status_update' => 'boolean',
        'is_spam' => 'boolean',
        'is_violation' => 'boolean',
        'spam_reports' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function idea(): BelongsTo
    {
        return $this->belongsTo(Idea::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function getStatusClasses(): string
    {
        $allStatuses = [
            'Nowy' => 'bg-gray-200',
            'Rozważane' => 'bg-green-700',
            'W realizacji' => 'bg-yellow-600',
            'Zrealizowane' => 'bg-blue-700',
            'Odrzucone' => 'bg-red-700',
        ];

        return $allStatuses[$this->status->name];
    }
}
