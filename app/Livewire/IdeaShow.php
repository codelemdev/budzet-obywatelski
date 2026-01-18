<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Idea;
use Livewire\Attributes\On;
use Livewire\Component;

class IdeaShow extends Component
{
    public $idea;

    public $status;

    public $statuses;

    public $votesCount;

    public $hasVoted;

    public $commentsCount;

    public $update_comment;

    public $comment;

    protected $rules = [
        'comment' => 'required|min:4',
    ];

    public function mount(Idea $idea, $votesCount)
    {
        $this->idea = $idea;
        $this->idea->loadCount('comments');
        $this->votesCount = $votesCount;
        $this->hasVoted = $idea->isVotedByUser(auth()->user());
        $this->status = $idea->status_id;
        $this->commentsCount = $idea->comments_count;
        $this->statuses = \App\Models\Status::all();
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

    public function setStatus()
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            abort(403);
        }

        $this->idea->update(['status_id' => $this->status]);
        $this->idea->refresh();
        $this->idea->loadCount('comments');
        $this->commentsCount = $this->idea->comments_count;

        \App\Models\Comment::create([
            'user_id' => auth()->id(),
            'idea_id' => $this->idea->id,
            'status_id' => $this->status,
            'body' => $this->update_comment ?? '',
            'is_status_update' => true,
        ]);

        $this->reset('update_comment');

        $this->dispatch('status-updated');
        $this->dispatch('comment-added');
        $this->dispatch('queryStringUpdatedStatus', $this->status);
        session()->flash('success_message', 'Status został zaktualizowany!');
    }

    public function postComment()
    {
        if (auth()->guest()) {
            abort(403);
        }

        $this->validate();

        \App\Models\Comment::create([
            'user_id' => auth()->id(),
            'idea_id' => $this->idea->id,
            'status_id' => $this->status,
            'body' => $this->comment,
            'is_status_update' => false,
        ]);

        $this->reset('comment');
        $this->idea->loadCount('comments');
        $this->commentsCount = $this->idea->comments_count;

        $this->dispatch('comment-added');
        session()->flash('success_message', 'Komentarz został dodany!');
    }

    #[On('comment-was-deleted')]
    public function commentDeleted()
    {
        $this->idea->refresh();
        $this->idea->loadCount('comments');
        $this->commentsCount = $this->idea->comments_count;
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
        return view('livewire.idea-show');
    }
}
