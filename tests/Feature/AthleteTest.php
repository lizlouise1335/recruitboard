<?php

// tests/Feature/AthleteTest.php
// Each test follows the same pattern: arrange (create data),
// act (send a fake request), assert (check the response).

namespace Tests\Feature;

use App\Models\Athlete;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AthleteTest extends TestCase
{
    // Gives every test a fresh, empty database (in memory, so it's fast),
    // so tests never see each other's data and can run in any order.
    use RefreshDatabase;

    // setUp() runs before EACH test in this class
    protected function setUp(): void
    {
        // Let Laravel do its own setup first (boot the app, reset the database)
        parent::setUp();
        // Pages will load built assets with @vite. Tests don't build them,
        // so tell Laravel to skip that instead of failing.
        $this->withoutVite();
    }

    public function test_athletes_page_lists_athletes(): void
    {
        // Arrange: one athlete with a name we know
        Athlete::factory()->create(['name' => 'Maya Okafor']);

        // Act + assert: the list page loads and the name appears in the HTML
        $this->get('/athletes')
            ->assertOk()
            ->assertSee('Maya Okafor');
    }

    public function test_api_filters_by_sport(): void
    {
        // Arrange: two athletes in different sports
        Athlete::factory()->create(['name' => 'Soccer Player', 'sport' => 'Soccer']);
        Athlete::factory()->create(['name' => 'Hoops Player', 'sport' => 'Basketball']);

        $this->getJson('/api/athletes?sport=Soccer')
            ->assertOk()
            // Exactly one athlete came back...
            ->assertJsonCount(1)
            // ...and the first one ("0" = first item) is the soccer player
            ->assertJsonPath('0.name', 'Soccer Player');
    }

    public function test_profile_page_shows_athlete(): void
    {
        // A random athlete. We don't care who, as long as their page works.
        $athlete = Athlete::factory()->create();

        // "{$athlete->id}" puts the athlete's id into the URL
        $this->get("/athletes/{$athlete->id}")
            ->assertOk()
            ->assertSee($athlete->name);
    }

    // The "unhappy path": what happens with bad input. Bugs like to hide here.
    public function test_missing_athlete_returns_404(): void
    {
        // The database is empty, so athlete 999 can't exist
        $this->get('/athletes/999')->assertNotFound();
    }
}
