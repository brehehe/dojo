<?php

use App\Models\Court\Court;
use App\Models\User;
use App\Services\StateCache;

test('early ETag validation returns 304 Not Modified on subsequent requests', function () {
    $admin = User::factory()->create();
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);

    // 1. First request gets 200 OK and an ETag
    $response1 = $this->actingAs($admin)
        ->getJson("/api/svelte-monitor/court/{$court->id}/state");

    $response1->assertSuccessful();
    $etag1 = $response1->headers->get('ETag');
    expect($etag1)->not->toBeNull();

    // 2. Second request with If-None-Match returns 304 Not Modified
    $response2 = $this->actingAs($admin)
        ->withHeaders(['If-None-Match' => $etag1])
        ->getJson("/api/svelte-monitor/court/{$court->id}/state");

    expect($response2->status())->toBe(304);

    // 3. Bump version key, now ETag should change and return 200 OK
    app(StateCache::class)->bumpCourt($court->id);

    $response3 = $this->actingAs($admin)
        ->withHeaders(['If-None-Match' => $etag1])
        ->getJson("/api/svelte-monitor/court/{$court->id}/state");

    $response3->assertSuccessful();
    $etag2 = $response3->headers->get('ETag');
    expect($etag2)->not->toBe($etag1);
});

test('scoring dashboard state supports ETag and returns 304 Not Modified', function () {
    $admin = User::factory()->create();

    $response1 = $this->actingAs($admin)
        ->getJson('/admin/api/scoring/dashboard-state');

    $response1->assertSuccessful();
    $etag1 = $response1->headers->get('ETag');
    expect($etag1)->not->toBeNull();

    $response2 = $this->actingAs($admin)
        ->withHeaders(['If-None-Match' => $etag1])
        ->getJson('/admin/api/scoring/dashboard-state');

    expect($response2->status())->toBe(304);
});
