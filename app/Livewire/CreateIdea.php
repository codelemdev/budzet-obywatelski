<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Category;
use App\Models\Idea;
use Illuminate\Http\Response;
use Livewire\Attributes\Rule;
use Livewire\Component;

class CreateIdea extends Component
{
    #[Rule('required|min:3')]
    public $title;

    #[Rule('required|integer')]
    public $category = 1;

    #[Rule('required|min:5')]
    public $description;

    public function createIdea()
    {
        if (auth()->check()) {
            $this->validate();

            Idea::create([
                'user_id' => auth()->id(),
                'category_id' => $this->category,
                'status_id' => 1,
                'title' => $this->title,
                'description' => $this->description,
            ]);

            session()->flash('success_message', 'Gratulacje! Twój pomysł został dodany pomyślnie. Inni użytkownicy mogą już na niego głosować!');

            $this->reset();

            return redirect()->route('idea.index');
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    public function render()
    {
        return view('livewire.create-idea', [
            'categories' => Category::all(),
        ]);
    }
}
