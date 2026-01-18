<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusLogicTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_count_returns_correct_counts_with_dynamic_ids()
    {
        // Create statuses with potentially mixed up IDs to simulate the bug scenario
        // Note: In RefreshDatabase, auto-increment starts at 1, so we can't easily force specific IDs
        // without disabling auto-increment checks, but we can just create them in a specific order
        // and rely on name matching.

        $statusNowy = Status::factory()->create(['name' => 'Nowy']);
        $statusRozwazane = Status::factory()->create(['name' => 'Rozważane']);
        $statusWRealizacji = Status::factory()->create(['name' => 'W realizacji']);
        $statusZrealizowane = Status::factory()->create(['name' => 'Zrealizowane']);
        $statusOdrzucone = Status::factory()->create(['name' => 'Odrzucone']);

        $user = User::factory()->create();
        $category = Category::factory()->create();

        // Create Ideas
        Idea::factory()->count(2)->create([
            'status_id' => $statusNowy->id,
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        Idea::factory()->count(3)->create([
            'status_id' => $statusZrealizowane->id,
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        // Act
        $counts = Status::getCount();

        // Assert
        $this->assertEquals(5, $counts['wszystkie_statuses']); // Total 2+3
        $this->assertEquals(2, $counts['Nowy']);
        $this->assertEquals(3, $counts['Zrealizowane']);
        $this->assertEquals(0, $counts['Rozważane']);
        $this->assertEquals(0, $counts['W realizacji']);
        $this->assertEquals(0, $counts['Odrzucone']);
    }

    public function test_status_get_classes()
    {
        $status = new Status(['name' => 'Rozważane']);
        $this->assertStringContainsString('text-green-700', $status->getClasses());

        $status = new Status(['name' => 'Zrealizowane']);
        $this->assertStringContainsString('text-blue-700', $status->getClasses());
    }
}
