<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Idea;
use Livewire\Component;

class IdeaIndex extends Component
{
    public $idea;

    public $votesCount;

    public $hasVoted;

    public $commentsCount;

    public function mount(Idea $idea, $votesCount)
    {
        $this->idea = $idea;
        $this->votesCount = $votesCount;
        $this->hasVoted = $idea->voted_by_user;
        $this->commentsCount = $idea->comments_count;
    }

    public function vote()
    {
        if (! auth()->check()) {
            return redirect(route('register'));
        }
        if ($this->hasVoted) {
            $this->idea->removeVote(auth()->user());
            $this->votesCount--;
            $this->hasVoted = false;
        } else {
            $this->idea->vote(auth()->user());
            $this->votesCount++;
            $this->hasVoted = true;
        }
    }

    public function deleteIdea()
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            $this->idea->votes()->detach();
            $this->idea->comments()->delete();
            $this->idea->delete();

            return redirect()->route('idea.index');
        }
    }

    public function render()
    {
        return view('livewire.idea-index');
    }
}
