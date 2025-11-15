<?php
namespace App\Models;

use PDO;

class Post {
    // Private connection method (same pattern as User)
    private static function connect(): PDO {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $db = getenv('DB_NAME') ?: 'authboard';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        return $pdo;
    }

    // Create a new post
    public static function create(int $userId, string $content, ?string $image = null): bool {
        $stmt = self::connect()->prepare('INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)');
        return $stmt->execute([$userId, $content, $image]);
    }

    // Fetch all posts with user info (newest first)
    public static function getAllWithUser(): array {
        $stmt = self::connect()->query('
            SELECT posts.*, users.name 
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            ORDER BY posts.created_at DESC
        ');
        return $stmt->fetchAll();
    }

    // Fetch all posts by a specific user (optional)
    public static function getByUser(int $userId): array {
        
        $stmt = self::connect()->prepare('
            SELECT posts.*, users.name 
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            WHERE user_id = ? 
            ORDER BY posts.created_at DESC
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    // Fetch a single post by id
    /**
     * @param int $postId
     * @return array|null
     */
    public static function getById(int $postId): ?array {
    $stmt = self::connect()->prepare('SELECT * FROM posts WHERE id = :id');
        $id = (int)$postId;
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ?: null;
    }

    // Delete a post by its ID (optional feature)
    /**
     * @param int $postId
     * @param int $userId
     * @return bool
     */
    public static function delete(int $postId, int $userId): bool {
        try {
            // Verify post exists and belongs to user
            $post = self::getById($postId);
            if (!$post || (int)$post['user_id'] !== (int)$userId) {
                return false;
            }

            // If there's an image, remove file from uploads directory
            if (!empty($post['image'])) {
                $uploadPath = __DIR__ . '/../../public/uploads/' . $post['image'];
                if (file_exists($uploadPath)) {
                    @unlink($uploadPath);
                }
            }

            $stmt = self::connect()->prepare('DELETE FROM posts WHERE id = :id AND user_id = :uid');
            $stmt->bindValue(':id', (int)$postId, PDO::PARAM_INT);
            $stmt->bindValue(':uid', (int)$userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\Throwable $e) {
            error_log('Post::delete error: ' . $e->getMessage());
            return false;
        }
    }

    // Update a post's content (only by owner)
    public static function update(int $postId, int $userId, string $content): bool {
        $stmt = self::connect()->prepare('UPDATE posts SET content = :content WHERE id = :id AND user_id = :uid');
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':id', (int)$postId, PDO::PARAM_INT);
        $stmt->bindValue(':uid', (int)$userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    // for editing the post 
    public static function edit(int $postId, int $userId, string $newContent): bool {
        try {
            // Verify post exists and belongs to user
            $post = self::getById($postId);
            if (!$post || (int)$post['user_id'] !== (int)$userId) {
                return false;
            }

            $stmt = self::connect()->prepare('UPDATE posts SET content = :content WHERE id = :id AND user_id = :uid');
            $stmt->bindValue(':content', $newContent, PDO::PARAM_STR);
            $stmt->bindValue(':id', (int)$postId, PDO::PARAM_INT);
            $stmt->bindValue(':uid', (int)$userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\Throwable $e) {
            error_log('Post::edit error: ' . $e->getMessage());
            return false;
        }
    }
}
