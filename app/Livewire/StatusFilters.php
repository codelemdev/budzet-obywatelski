<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Status;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class StatusFilters extends Component
{
    public $status;

    public $statusCount;

    public function mount()
    {
        $this->statusCount = Status::getCount();
        $this->status = request()->status ?? 'Wszystkie';

        if (Route::currentRouteName() === 'idea.show') {
            $this->status = null;
        }
    }

    public function setStatus($newStatus)
    {
        $this->status = $newStatus;
        $this->dispatch('queryStringUpdatedStatus', $newStatus);

        if ($this->getPreviousRouteName() === 'idea.show') {
            return redirect()->route('idea.index', [
                'status' => $this->status,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.status-filters');
    }

    // Powinno powstrzymać odświeżanie strony, gdy zmienia się kategorie z nav, ale nie działa. Nie wiem, nie kumam.
    public function getPreviousRouteName()
    {
        return app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();
    }
}
