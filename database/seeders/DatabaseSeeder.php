<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->faker = \Faker\Factory::create('pl_PL');

        // 1. Create Admin
        $admin = User::factory()->create([
            'name' => 'Adam Adminowicz',
            'email' => 'admin@budzet-obywatelski.pl',
            'password' => bcrypt('password'),
            'role' => \App\Enums\Role::Admin,
        ]);

        // 2. Create Moderator
        $moderator = User::factory()->create([
            'name' => 'Ewa Moderska',
            'email' => 'moderator@budzet-obywatelski.pl',
            'password' => bcrypt('password'),
            'role' => \App\Enums\Role::Moderator,
        ]);

        // 3. Create Test User
        $testUser = User::factory()->create([
            'name' => 'Jan Kowalski',
            'email' => 'jan.kowalski@test.pl',
            'password' => bcrypt('password'),
            'role' => \App\Enums\Role::User,
        ]);

        // 3. Create 100 Random Users (Total 103 users)
        $regularUsers = User::factory(100)->create();

        // Prepare pool of eligible users for activities (exclude Admin, Moderator and Test User)
        $eligibleUsers = $regularUsers;

        // 4. Create Categories and Statuses
        $categories = collect([
            Category::factory()->create(['name' => 'Kultura']),
            Category::factory()->create(['name' => 'Sport']),
            Category::factory()->create(['name' => 'Infrastruktura']),
            Category::factory()->create(['name' => 'Zdrowie']),
        ]);

        $statusNowy = Status::factory()->create(['name' => 'Nowy']);
        $statusZrealizowane = Status::factory()->create(['name' => 'Zrealizowane']);

        $otherStatuses = collect([
            Status::factory()->create(['name' => 'Rozważane']),
            Status::factory()->create(['name' => 'W realizacji']),
            Status::factory()->create(['name' => 'Odrzucone']),
        ]);

        // 5. Create Ideas
        $ideas = collect();

        // 5a. Exactly 4 "Zrealizowane" ideas (Older dates: Jan - June 2025)
        for ($i = 0; $i < 4; $i++) {
            $ideas->push(Idea::factory()->create([
                'user_id' => $eligibleUsers->random()->id,
                'category_id' => $categories->random()->id,
                'status_id' => $statusZrealizowane->id,
                'created_at' => $this->faker->dateTimeBetween('2025-01-01', '2025-06-30'),
            ]));
        }

        // 5b. Create remaining 96 ideas
        $allStatusesExceptZrealizowane = $otherStatuses->push($statusNowy);

        for ($i = 0; $i < 96; $i++) {
            $status = $allStatusesExceptZrealizowane->random();

            // Date logic
            $createdAt = match ($status->name) {
                'Nowy' => $this->faker->dateTimeBetween('-1 month', 'now'), // Fresh
                default => $this->faker->dateTimeBetween('2025-01-01', 'now'), // Random in range
            };

            $ideas->push(Idea::factory()->create([
                'user_id' => $eligibleUsers->random()->id,
                'category_id' => $categories->random()->id,
                'status_id' => $status->id,
                'created_at' => $createdAt,
            ]));
        }

        // 6. Comments Logic
        foreach ($ideas as $idea) {
            $limit = ($idea->status_id == $statusNowy->id) ? 2 : 14;
            $commentCount = rand(0, $limit);

            for ($j = 0; $j < $commentCount; $j++) {
                Comment::factory()->create([
                    'idea_id' => $idea->id,
                    'user_id' => $eligibleUsers->random()->id,
                    'created_at' => $this->faker->dateTimeBetween($idea->created_at, 'now'),
                ]);
            }
        }

        // 7. Votes Logic (Refined 3-Tier Model)

        // Tier 1: TOP (Unique votes 45-78)
        // Must contain all "Zrealizowane" (4) + ~14 others (total ~18)
        // Must NOT contain "Nowy"

        $zrealizowaneIdeas = $ideas->where('status_id', $statusZrealizowane->id);

        // Candidates for Top/Mid (excluding Zrealizowane and Nowy)
        $candidates = $ideas->where('status_id', '!=', $statusZrealizowane->id)
            ->where('status_id', '!=', $statusNowy->id);

        // We want Top Tier to have roughly 18 ideas (4 Zrealizowane + 14 others)
        $topTierOthersCount = 14;
        $topTierOthers = $candidates->random(min($topTierOthersCount, $candidates->count()));
        $topTier = $zrealizowaneIdeas->merge($topTierOthers);

        // Remove Top Tier candidates from pool
        $remainingCandidates = $candidates->diff($topTierOthers);

        // Tier 2: MID (Roughly 15 ideas, range 15-44)
        $midTierCount = 15;
        $midTier = $remainingCandidates->random(min($midTierCount, $remainingCandidates->count()));

        // Tier 3: LOW (Everyone else + Nowy, range 0-14)
        $lowTier = $ideas->diff($topTier)->diff($midTier);

        // --- Voting Execution ---

        // 1. TOP TIER (Unique Votes)
        // Generate unique vote counts from 45 to 78
        $topVoteCounts = range(45, 78);
        shuffle($topVoteCounts);

        foreach ($topTier as $idea) {
            // Pop one unique count
            $voteCount = array_pop($topVoteCounts);

            // Fallback if we somehow run out of unique numbers (unlikely with this config)
            if ($voteCount === null) {
                $voteCount = 78;
            }

            $voters = $eligibleUsers->random(min($voteCount, $eligibleUsers->count()));
            foreach ($voters as $voter) {
                Vote::factory()->create([
                    'idea_id' => $idea->id,
                    'user_id' => $voter->id,
                    'created_at' => $this->faker->dateTimeBetween($idea->created_at, 'now'),
                ]);
            }
        }

        // 2. MID TIER (Moderate Votes 15-44)
        foreach ($midTier as $idea) {
            $voteCount = rand(15, 44);
            $voters = $eligibleUsers->random(min($voteCount, $eligibleUsers->count()));
            foreach ($voters as $voter) {
                Vote::factory()->create([
                    'idea_id' => $idea->id,
                    'user_id' => $voter->id,
                    'created_at' => $this->faker->dateTimeBetween($idea->created_at, 'now'),
                ]);
            }
        }

        // 3. LOW TIER (Low Votes 0-14)
        foreach ($lowTier as $idea) {
            $voteCount = rand(0, 14);
            if ($voteCount > 0) {
                $voters = $eligibleUsers->random(min($voteCount, $eligibleUsers->count()));
                foreach ($voters as $voter) {
                    Vote::factory()->create([
                        'idea_id' => $idea->id,
                        'user_id' => $voter->id,
                        'created_at' => $this->faker->dateTimeBetween($idea->created_at, 'now'),
                    ]);
                }
            }
        }
    }
}
