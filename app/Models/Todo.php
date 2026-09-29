<?php

namespace App\Models;

final class Todo
{
    public static function getAll(): array
    {
        return [];
    }

    public static function create(array $data): int
    {
        return 1;
    }

    public static function update(array $data, int $id): int
    {
        return 1;
    }

    public static function findById(int $id): array
    {
        return [];
    }

     public static function destroy(int $id): int
    {
        return 1;
    }
}
