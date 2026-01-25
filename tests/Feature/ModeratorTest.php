<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ModeratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_moderator_can_mark_idea_as_spam()
    {
        $moderator = User::factory()->create(['role' => Role::Moderator]);
        $category = Category::factory()->create();
        $status = Status::factory()->create(['name' => 'Nowy']);
        $idea = Idea::factory()->create([
            'category_id' => $category->id,
            'status_id' => $status->id,
        ]);

        Livewire::actingAs($moderator)
            ->test(\App\Livewire\IdeaShow::class, ['idea' => $idea, 'votesCount' => 0])
            ->call('markAsSpam')
            ->assertDispatched('idea-was-marked-as-spam');

        $this->assertTrue((bool) $idea->fresh()->is_spam);
    }

    public function test_moderator_can_mark_idea_as_violation_and_toggle()
    {
        $moderator = User::factory()->create(['role' => Role::Moderator]);
        $category = Category::factory()->create();
        $status = Status::factory()->create(['name' => 'Nowy']);
        $idea = Idea::factory()->create([
            'category_id' => $category->id,
            'status_id' => $status->id,
        ]);

        // 1. Mark as Violation
        Livewire::actingAs($moderator)
            ->test(\App\Livewire\IdeaShow::class, ['idea' => $idea, 'votesCount' => 0])
            ->call('markAsViolation')
            ->assertDispatched('idea-was-marked-as-violation');

        $this->assertTrue((bool) $idea->fresh()->is_violation);
        $this->assertFalse((bool) $idea->fresh()->is_spam);

        // 2. Toggle Violation (should unmark)
        Livewire::actingAs($moderator)
            ->test(\App\Livewire\IdeaShow::class, ['idea' => $idea, 'votesCount' => 0])
            ->call('markAsViolation');

        $this->assertFalse((bool) $idea->fresh()->is_violation);

        // 3. Mark as Spam
        Livewire::actingAs($moderator)
            ->test(\App\Livewire\IdeaShow::class, ['idea' => $idea, 'votesCount' => 0])
            ->call('markAsSpam');

        $this->assertTrue((bool) $idea->fresh()->is_spam);

        // 4. Switch to Violation (should clear Spam)
        Livewire::actingAs($moderator)
            ->test(\App\Livewire\IdeaShow::class, ['idea' => $idea, 'votesCount' => 0])
            ->call('markAsViolation');

        $this->assertTrue((bool) $idea->fresh()->is_violation);
        $this->assertFalse((bool) $idea->fresh()->is_spam);
    }

    public function test_regular_user_cannot_mark_idea_as_spam()
    {
        $user = User::factory()->create(['role' => Role::User]);
        $category = Category::factory()->create();
        $status = Status::factory()->create(['name' => 'Nowy']);
        $idea = Idea::factory()->create([
            'category_id' => $category->id,
            'status_id' => $status->id,
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\IdeaShow::class, ['idea' => $idea, 'votesCount' => 0])
            ->call('markAsSpam')
            ->assertForbidden();

        $this->assertFalse((bool) $idea->fresh()->is_spam);
    }

    public function test_moderator_can_mark_comment_as_spam()
    {
        $moderator = User::factory()->create(['role' => Role::Moderator]);
        $category = Category::factory()->create();
        $status = Status::factory()->create(['name' => 'Nowy']);
        $idea = Idea::factory()->create([
            'category_id' => $category->id,
            'status_id' => $status->id,
        ]);
        $comment = Comment::factory()->create(['idea_id' => $idea->id]);

        Livewire::actingAs($moderator)
            ->test(\App\Livewire\IdeaComments::class, ['idea' => $idea])
            ->call('markAsSpam', $comment->id)
            ->assertDispatched('comment-was-marked-as-spam');

        $this->assertTrue((bool) $comment->fresh()->is_spam);
    }

    public function test_moderator_can_mark_comment_as_violation()
    {
        $moderator = User::factory()->create(['role' => Role::Moderator]);
        $category = Category::factory()->create();
        $status = Status::factory()->create(['name' => 'Nowy']);
        $idea = Idea::factory()->create([
            'category_id' => $category->id,
            'status_id' => $status->id,
        ]);
        $comment = Comment::factory()->create(['idea_id' => $idea->id]);

        Livewire::actingAs($moderator)
            ->test(\App\Livewire\IdeaComments::class, ['idea' => $idea])
            ->call('markAsViolation', $comment->id)
            ->assertDispatched('comment-was-marked-as-violation');

        $this->assertTrue((bool) $comment->fresh()->is_violation);
    }
}
