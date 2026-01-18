<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function ideas(): HasMany
    {
        return $this->hasMany(Idea::class);
    }

    public static function getCount(): array
    {
        return once(function () {
            $statuses = Status::all()->pluck('id', 'name');

            return Idea::query()
                ->selectRaw('count(*) as wszystkie_statuses')
                ->selectRaw("count(case when status_id = ? then 1 end) as 'Nowy'", [$statuses->get('Nowy', 0)])
                ->selectRaw("count(case when status_id = ? then 1 end) as 'Rozważane'", [$statuses->get('Rozważane', 0)])
                ->selectRaw("count(case when status_id = ? then 1 end) as 'W realizacji'", [$statuses->get('W realizacji', 0)])
                ->selectRaw("count(case when status_id = ? then 1 end) as 'Zrealizowane'", [$statuses->get('Zrealizowane', 0)])
                ->selectRaw("count(case when status_id = ? then 1 end) as 'Odrzucone'", [$statuses->get('Odrzucone', 0)])
                ->first()
                ->toArray();
        });
    }

    public function getClasses(): string
    {
        return match ($this->name) {
            'Nowy' => 'bg-gray-200 text-gray-600',
            'Rozważane' => 'bg-gray-200 text-green-700',
            'W realizacji' => 'bg-gray-200 text-yellow-600',
            'Zrealizowane' => 'bg-gray-200 text-blue-700',
            'Odrzucone' => 'bg-gray-200 text-red-700',
            default => 'bg-gray-200 text-gray-600',
        };
    }
}
