<?php

use App\Livewire\Auth\NewLoginIndex;
use Livewire\Livewire;

test('it clears API URLs from url.intended in session', function () {
    $apiUrl = 'https://smart-perkemi.id/admin/api/scoring/embu/24/state?pool_id=5&round=Penyisihan';

    session(['url.intended' => $apiUrl]);

    Livewire::test(NewLoginIndex::class);

    expect(session()->has('url.intended'))->toBeFalse();
});

test('it does not clear non-API URLs from url.intended in session', function () {
    $nonApiUrl = 'https://smart-perkemi.id/admin/dashboard';

    session(['url.intended' => $nonApiUrl]);

    Livewire::test(NewLoginIndex::class);

    expect(session()->get('url.intended'))->toBe($nonApiUrl);
});

test('it clears logout URL from url.intended in session', function () {
    $logoutUrl = 'https://smart-perkemi.id/logout';

    session(['url.intended' => $logoutUrl]);

    Livewire::test(NewLoginIndex::class);

    expect(session()->has('url.intended'))->toBeFalse();
});
