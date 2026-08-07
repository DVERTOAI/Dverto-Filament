<?php

namespace App\Support;

class Rooms
{
    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        /** @var array<string, string> $rooms */
        $rooms = config('rooms.list', []);

        return $rooms;
    }

    public static function label(?string $key): ?string
    {
        if ($key === null || $key === '') {
            return null;
        }

        return self::options()[$key] ?? $key;
    }

    /**
     * @param  list<string>|null  $keys
     * @return list<string>
     */
    public static function labels(?array $keys): array
    {
        if ($keys === null || $keys === []) {
            return [];
        }

        return collect($keys)
            ->map(static fn (string $key): string => self::label($key) ?? $key)
            ->values()
            ->all();
    }
}
