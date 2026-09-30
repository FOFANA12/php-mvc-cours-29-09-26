<?php

namespace App\Models;

use App\Core\Database;

final class Todo
{
    public static function getAll(): array
    {
        $db = Database::connect();

        $sql = "SELECT * FROM todos ORDER BY created_at DESC";

        $stmt = $db->query($sql);

        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $db = Database::connect();

        $sql = "
            INSERT INTO todos (titre, statut)
            VALUES (:titre, :statut)
        ";

        $stmt = $db->prepare($sql);

        $stmt->execute([
            'titre' => trim($data['titre']),
            'statut' => $data['statut'] ?? 0,
        ]);

        return (int) $db->lastInsertId();
    }

    public static function update(array $data, int $id): int
    {
        $db = Database::connect();

        $sql = "
            UPDATE todos
            SET titre = :titre,
                statut = :statut
            WHERE id = :id
        ";

        $stmt = $db->prepare($sql);

        $stmt->execute([
            'titre' => trim($data['titre']),
            'statut' => $data['statut'],
            'id' => $id,
        ]);

        return $stmt->rowCount();
    }

    public static function findById(int $id): array
    {
        $db = Database::connect();

        $sql = "SELECT * FROM todos WHERE id = :id";

        $stmt = $db->prepare($sql);

        $stmt->execute([
            'id' => $id,
        ]);

        $todo = $stmt->fetch();

        return $todo ?: [];
    }

    public static function destroy(int $id): int
    {
        $db = Database::connect();

        $sql = "DELETE FROM todos WHERE id = :id";

        $stmt = $db->prepare($sql);

        $stmt->execute([
            'id' => $id,
        ]);

        return $stmt->rowCount();
    }
}