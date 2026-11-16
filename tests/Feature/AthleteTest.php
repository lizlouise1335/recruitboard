<?php

// tests/Feature/AthleteTest.php

namespace Tests\Feature;

use App\Models\Athlete;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AthleteTest extends TestCase
{
    // Gives every test a fresh, empty in-memory database
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Pages will load built assets with @vite. Tests don't build them.
        $this->withoutVite();
    }

    public function test_athletes_page_lists_athletes(): void
    {
        Athlete::factory()->create(['name' => 'Maya Okafor']);

        $this->get('/athletes')
            ->assertOk()
            ->assertSee('Maya Okafor');
    }

    public function test_api_filters_by_sport(): void
    {
        Athlete::factory()->create(['name' => 'Soccer Player', 'sport' => 'Soccer']);
        Athlete::factory()->create(['name' => 'Hoops Player', 'sport' => 'Basketball']);

        $this->getJson('/api/athletes?sport=Soccer')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.name', 'Soccer Player');
    }

    public function test_profile_page_shows_athlete(): void
    {
        $athlete = Athlete::factory()->create();

        $this->get("/athletes/{$athlete->id}")
            ->assertOk()
            ->assertSee($athlete->name);
    }

    public function test_missing_athlete_returns_404(): void
    {
        $this->get('/athletes/999')->assertNotFound();
    }
}
