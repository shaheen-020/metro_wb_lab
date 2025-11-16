<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Post;
use App\Models\Activity;

class PostController extends Controller {
    public function create(): void
    {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = trim($_POST['content']);
            $imagePath = null;

            if (!empty($_FILES['image']['name'])) {
                $uploadDir = __DIR__ . '/../../public/uploads/';

                // Create directory if not exists
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Only allow image extensions
                $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

                if (in_array($ext, $allowedExt)) {
                    // Give a unique name to prevent overwriting
                    $imageName = time() . '_' . preg_replace("/[^a-zA-Z0-9_-]/", "", basename($_FILES['image']['name']));
                    $targetFile = $uploadDir . $imageName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        $imagePath = $imageName; // Store filename in DB
                    }
                }
            }

            // Create the post
            $postId = Post::create($user['id'], $content, $imagePath);
            
            // Log activity
            Activity::log($user['id'], 'create_post', $postId, 'Created a new post');

            // Redirect to dashboard
            header('Location: /dashboard');
            exit;
        }

        // Show form
        $this->view('create_post.php', ['user' => $user]);
    }

    // Handle post deletion (only owner may delete)
    public function delete(): void
    {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        // Only accept POST requests for deletion
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /dashboard');
            exit;
        }

        $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
        if ($postId <= 0) {
            header('Location: /dashboard');
            exit;
        }

        // Verify post exists and belongs to user
        $target = Post::getById($postId);
        if (!$target || (int)$target['user_id'] !== (int)$user['id']) {
            // not found or not owner
            header('Location: /dashboard');
            exit;
        }

        // Delete via model (model will verify ownership and remove image file)
        $deleted = Post::delete($postId, $user['id']);
        if (!$deleted) {
            // Log for debugging and set a flash message
            error_log("Failed to delete post id={$postId} for user id={$user['id']}");
            Session::set('flash', ['type' => 'error', 'message' => 'Could not delete the post.']);
            // Add debug payload (printed to browser console via layout)
            Session::set('debug', [
                'where' => 'delete_failed',
                'postId' => $postId,
                'userId' => $user['id'],
                'target' => $target,
            ]);
        } else {
            Activity::log($user['id'], 'delete_post', $postId, 'Deleted a post');
            Session::set('flash', ['type' => 'success', 'message' => 'Post deleted.']);
            Session::set('debug', [
                'where' => 'delete_ok',
                'postId' => $postId,
                'userId' => $user['id'],
            ]);
        }

        // Redirect back to dashboard
        header('Location: /dashboard');
        exit;
    }

    // Handle post edit (only owner may edit content)
    public function edit(): void
    {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /dashboard');
            exit;
        }

        $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
        $content = isset($_POST['content']) ? trim($_POST['content']) : '';

        if ($postId <= 0 || $content === '') {
            Session::set('flash', ['type' => 'error', 'message' => 'Invalid post or content.']);
            header('Location: /dashboard');
            exit;
        }

        // Verify ownership
        $target = Post::getById($postId);
        if (!$target || (int)$target['user_id'] !== (int)$user['id']) {
            Session::set('flash', ['type' => 'error', 'message' => 'Not authorized to edit this post.']);
            header('Location: /dashboard');
            exit;
        }

        $ok = Post::update($postId, $user['id'], $content);
        if ($ok) {
            Activity::log($user['id'], 'edit_post', $postId, 'Edited a post');
            Session::set('flash', ['type' => 'success', 'message' => 'Post updated.']);
            Session::set('debug', ['where' => 'edit_ok', 'postId' => $postId]);
        } else {
            Session::set('flash', ['type' => 'error', 'message' => 'Could not update post.']);
            Session::set('debug', ['where' => 'edit_failed', 'postId' => $postId]);
        }

        header('Location: /dashboard');
        exit;
    }
}
