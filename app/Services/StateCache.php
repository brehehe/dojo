<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StateCache
{
    public function version(string $scope, int|string|null $id = null): int
    {
        return (int) Cache::get($this->versionKey($scope, $id), 1);
    }

    public function bump(string $scope, int|string|null $id = null): void
    {
        $key = $this->versionKey($scope, $id);

        if (! Cache::has($key)) {
            Cache::forever($key, 1);
        }

        Cache::increment($key);
    }

    public function bumpCourt(int|string|null $courtId, bool $bumpDashboard = true): void
    {
        if ($courtId) {
            $this->bump('court', $courtId);
        }

        if ($bumpDashboard) {
            $this->bump('dashboard');
        }
    }

    public function bumpMatch(int|string|null $matchId): void
    {
        if ($matchId) {
            $this->bump('match', $matchId);
        }

        $this->bump('dashboard');
    }

    public function cacheKey(string $prefix, array $parts = [], array $versions = []): string
    {
        return $prefix.':'.md5(json_encode([$parts, $versions]));
    }

    public function makeEtag(Request $request, array $versions = []): string
    {
        return '"'.sha1(json_encode([$request->getPathInfo(), $versions])).'"';
    }

    public function hasValidEtag(Request $request, array $versions = []): bool
    {
        $etag = $this->makeEtag($request, $versions);

        return collect(explode(',', (string) $request->headers->get('If-None-Match')))
            ->map(fn ($value) => trim($value))
            ->contains($etag);
    }

    public function respond304(Request $request, array $versions = [], int $maxAge = 3): JsonResponse
    {
        $etag = $this->makeEtag($request, $versions);

        return response()->json(null, 304)
            ->header('Cache-Control', "max-age={$maxAge}, must-revalidate")
            ->header('ETag', $etag);
    }

    public function conditionalJson(Request $request, mixed $data, array $versions = [], int $maxAge = 3): JsonResponse
    {
        $etag = $this->makeEtag($request, $versions);

        if ($this->hasValidEtag($request, $versions)) {
            return $this->respond304($request, $versions, $maxAge);
        }

        return response()->json($data)
            ->header('Cache-Control', "max-age={$maxAge}, must-revalidate")
            ->header('ETag', $etag);
    }

    private function versionKey(string $scope, int|string|null $id = null): string
    {
        return 'state_version:'.$scope.($id !== null ? ':'.$id : '');
    }

    private function withoutVolatileFields(mixed $value): mixed
    {
        if ($value instanceof \JsonSerializable) {
            $value = $value->jsonSerialize();
        } elseif (is_object($value) && method_exists($value, 'toArray')) {
            $value = $value->toArray();
        } elseif (is_object($value)) {
            $value = get_object_vars($value);
        }

        if (! is_array($value)) {
            return $value;
        }

        unset($value['server_time_ms']);

        return array_map(fn ($item) => $this->withoutVolatileFields($item), $value);
    }
}
