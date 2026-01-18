<?php

declare(strict_types=1);

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read bool|null $voted_by_user
 */
class Idea extends Model
{
    use HasFactory;
    use Sluggable;

    const PAGINATION_COUNT = 10;

    protected $guarded = [];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array<string, array<string, string>>
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function votes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'votes');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getStatusClasses(): string
    {
        $allStatuses = [
            'Nowy' => 'bg-gray-200',
            'Rozważane' => 'bg-green-700 text-white',
            'W realizacji' => 'bg-yellow-600 text-white',
            'Zrealizowane' => 'bg-blue-700 text-white',
            'Odrzucone' => 'bg-red-700 text-white',
        ];

        return $allStatuses[$this->status->name];
    }

    public function isVotedByUser(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return Vote::where('user_id', $user->id)
            ->where('idea_id', $this->id)
            ->exists();
    }

    public function vote(User $user): void
    {
        Vote::create([
            'idea_id' => $this->id,
            'user_id' => $user->id,
        ]);
    }

    public function removeVote(User $user): void
    {
        Vote::where('idea_id', $this->id)
            ->where('user_id', $user->id)
            ->first()
            ->delete();
    }
}
