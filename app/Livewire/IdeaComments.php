<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Idea;
use Livewire\Attributes\Rule;
use Livewire\Component;

class IdeaComments extends Component
{
    public Idea $idea;

    #[Rule('required|min:3')]
    public $comment;

    #[\Livewire\Attributes\On('comment-added')]
    public function refresh()
    {
        // refreshing component
    }

    public function postComment()
    {
        if (auth()->guest()) {
            return redirect()->route('login');
        }

        $this->validate();

        $this->idea->comments()->create([
            'user_id' => auth()->id(),
            'body' => $this->comment,
        ]);

        $this->reset('comment');

        session()->flash('success_message', 'Komentarz został dodany!');
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        if (auth()->guest() || ! auth()->user()->isAdmin()) {
            abort(403);
        }

        $comment->delete();

        $this->dispatch('comment-was-deleted');
        session()->flash('success_message', 'Komentarz został usunięty!');
    }

    public function render()
    {
        return view('livewire.idea-comments', [
            'comments' => $this->idea->comments()->with('user')->latest()->get(),
        ]);
    }
}
