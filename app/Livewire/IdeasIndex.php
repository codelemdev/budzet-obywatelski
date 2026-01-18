<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\Vote;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class IdeasIndex extends Component
{
    use WithPagination;

    #[Url]
    public $status;

    #[Url]
    public $category;

    #[Url]
    public $filter;

    #[Url]
    public $search;

    public function mount()
    {
        $this->status = request()->status ?? 'Wszystkie';
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        if ($this->filter === 'Moje pomysły') {
            if (! auth()->check()) {
                return redirect()->route('login');
            }
        }
    }

    #[On('queryStringUpdatedStatus')]
    public function queryStringUpdatedStatus($newStatus)
    {
        $this->resetPage();
        $this->status = $newStatus;

        if ($this->filter === 'Nowe' && $newStatus !== 'Wszystkie') {
            $this->filter = 'Bez filtra';
        }
    }

    public function render()
    {
        $statuses = Status::pluck('id', 'name');
        $categories = Category::all();

        return view('livewire.ideas-index', [
            'ideas' => Idea::with('user', 'category', 'status')
                ->when($this->status && $this->status !== 'Wszystkie', function ($query) use ($statuses) {
                    return $query->where('status_id', $statuses->get($this->status));
                })->when($this->category && $this->category !== 'Wszystkie', function ($query) use ($categories) {
                    return $query->where('category_id', $categories->pluck('id', 'name')->get($this->category));
                })->when($this->filter && $this->filter === 'Najlepsze', function ($query) {
                    return $query->orderByDesc('votes_count');
                })->when($this->filter && $this->filter === 'Moje pomysły', function ($query) {
                    return $query->where('user_id', auth()->id());
                })->when($this->filter && $this->filter === 'Nowe', function ($query) use ($statuses) {
                    return $query->where('status_id', $statuses->get('Nowy'));
                })->when($this->search && strlen($this->search) >= 3, function ($query) {
                    return $query->where('title', 'like', '%'.$this->search.'%');
                })
                ->addSelect([
                    'voted_by_user' => Vote::select('id')
                        ->where('user_id', auth()->id())
                        ->whereColumn('idea_id', 'ideas.id'),
                ])
                ->withCount('votes')
                ->withCount('comments')
                ->latest('id')
                ->simplePaginate(Idea::PAGINATION_COUNT),
            'categories' => $categories,
        ]);
    }

    public function paginationView()
    {
        return 'pagination.custom';
    }
}
