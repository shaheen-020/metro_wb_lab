<?php

namespace App\Models;

use PDO;

class Activity
{
    /**
     * Log an activity for a user
     */
    public static function log(int $userId, string $actionType, ?int $postId = null, ?string $description = null): bool
    {
        try {
            $stmt = self::connect()->prepare(
                'INSERT INTO activities (user_id, action_type, post_id, description) VALUES (?, ?, ?, ?)'
            );
            return $stmt->execute([$userId, $actionType, $postId, $description]);
        } catch (\Exception $e) {
            error_log('Activity::log error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all activities for a specific user
     */
    public static function getByUser(int $userId): array
    {
        try {
            $stmt = self::connect()->prepare('
                SELECT a.*, p.content, p.image, u.name as user_name 
                FROM activities a
                LEFT JOIN posts p ON a.post_id = p.id
                LEFT JOIN users u ON a.user_id = u.id
                WHERE a.user_id = ?
                ORDER BY a.created_at DESC
                LIMIT 100
            ');
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            error_log('Activity::getByUser error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Database connection
     */
    private static function connect(): PDO
    {
        $dsn = 'mysql:host=localhost;dbname=metro_wb_lab;charset=utf8mb4';
        $username = 'root';
        $password = '';

        return new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
}
