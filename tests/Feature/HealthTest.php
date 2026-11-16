<?php

// tests/Feature/HealthTest.php
// A feature test sends a fake HTTP request through the whole app and checks
// the response. No server and no browser are needed, so it runs in milliseconds.

namespace Tests\Feature;

use Tests\TestCase;

// TestCase boots a fresh copy of your Laravel app for each test
class HealthTest extends TestCase
{
    // PHPUnit runs every public method whose name starts with "test".
    // A long, descriptive name tells you what broke when it fails.
    public function test_health_endpoint_returns_ok(): void
    {
        // Send GET /api/health, asking for JSON...
        $this->getJson('/api/health')
            // ...check that the status code is 200 OK...
            ->assertOk()
            // ...and that the body is exactly {"status":"ok"}, nothing more or less.
            ->assertExactJson(['status' => 'ok']);
    }
}
